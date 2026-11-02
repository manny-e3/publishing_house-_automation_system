<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Models\ChannelListing;
use App\Models\PhysicalInventory;
use App\Enums\DistributionChannel;
use App\Enums\WorkflowStatus;
use Illuminate\Http\Request;

class DistributionDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::whereIn('status', [
                WorkflowStatus::ReadyForPrint->value,
                WorkflowStatus::Published->value,
                'ready_for_print', 'published', 'in_design'
            ])
            ->with(['channelListings', 'physicalInventory']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('book_title', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Prioritize titles awaiting Gate 4 release validation at the top
        $manuscripts = $query
            ->orderByRaw("CASE WHEN status = 'ready_for_print' THEN 0 WHEN status = 'in_design' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Low stock physical inventory records (less than or equal to reorder threshold)
        $lowStockItems = PhysicalInventory::with('prospect')
            ->where('quantity_in_stock', '<=', 20)
            ->get();

        $channels = DistributionChannel::cases();

        return view('dashboards.distribution', compact('manuscripts', 'lowStockItems', 'channels'));
    }

    /**
     * Activate or update a channel listing for a manuscript.
     * Enforces Gate 3: File-to-Channel Access Gate!
     */
    public function updateChannel(Request $request, Prospect $prospect)
    {
        $request->validate([
            'channel' => 'required|string',
            'is_live' => 'required|boolean',
            'listing_url' => 'nullable|url',
            'retail_price' => 'nullable|numeric',
        ]);

        $channelSlug = $request->channel;
        $isLive = $request->boolean('is_live');

        // =========================================================================
        // Gate 3: Channel Listing Gate
        // Cannot activate Selar without verified ePub file
        // Cannot activate Amazon KDP without verified Amazon Ready KDP package
        // =========================================================================
        if ($isLive) {
            if ($channelSlug === DistributionChannel::Selar->value && empty($prospect->epub_file_url)) {
                return redirect()->back()->with('error', 'Gate 3 Access Gate: You cannot activate Selar storefront without a verified ePub file upload.');
            }

            if ($channelSlug === DistributionChannel::AmazonKdp->value && empty($prospect->amazon_kdp_ready_url)) {
                return redirect()->back()->with('error', 'Gate 3 Access Gate: You cannot activate Amazon KDP distribution without a verified Amazon Ready KDP package upload.');
            }
        }

        ChannelListing::updateOrCreate(
            [
                'prospect_id' => $prospect->id,
                'channel' => $channelSlug,
            ],
            [
                'is_live' => $isLive,
                'listing_url' => $request->listing_url,
                'retail_price' => $request->retail_price ?? 0.00,
                'published_at' => $isLive ? now() : null,
            ]
        );

        \App\Models\AuditLog::record('gate3_channel_updated', $prospect, [
            'channel' => $channelSlug,
            'is_live' => $isLive,
            'book_title' => $prospect->book_title,
        ]);

        return redirect()->back()->with('success', "Channel listing for " . ucfirst(str_replace('_', ' ', $channelSlug)) . " updated successfully.");
    }

    /**
     * Record or update physical inventory for a manuscript
     */
    public function addInventory(Request $request, Prospect $prospect)
    {
        $request->validate([
            'format' => 'nullable|string|in:paperback,hardcover',
            'quantity_ordered' => 'required|integer|min:1',
            'quantity_in_stock' => 'required|integer|min:0',
            'warehouse_location' => 'required|string|max:100',
        ]);

        $format = $request->input('format', 'paperback');

        PhysicalInventory::updateOrCreate(
            [
                'prospect_id' => $prospect->id,
                'format' => $format,
            ],
            [
                'quantity_ordered' => $request->quantity_ordered,
                'quantity_in_stock' => $request->quantity_in_stock,
                'warehouse_location' => $request->warehouse_location,
                'reorder_threshold' => 20,
            ]
        );

        return redirect()->back()->with('success', "Inventory for {$prospect->book_title} ({$format}) updated ({$request->quantity_in_stock} copies in stock).");
    }

    /**
     * Release validation toggle: Marks global release validation, clearing Gate 4
     * for Author Portal Digital Bookshelf downloads.
     */
    public function releaseValidation(Request $request, Prospect $prospect)
    {
        $metadata = $prospect->metadata_file ?? [];
        $validated = $request->boolean('validated', true);

        $metadata['distribution_validated'] = $validated;
        $metadata['distribution_validated_at'] = $validated ? now()->toIso8601String() : null;
        $metadata['distribution_validated_by'] = auth()->id();

        if ($validated) {
            $prospect->status = WorkflowStatus::Published->value;
        }

        $prospect->metadata_file = $metadata;
        $prospect->save();

        \App\Models\AuditLog::record($validated ? 'gate4_distribution_release_validated' : 'gate4_distribution_release_revoked', $prospect, [
            'book_title' => $prospect->book_title,
            'validated' => $validated,
            'author_id' => $prospect->user_id,
        ]);

        $statusText = $validated ? 'validated and unlocked for Author downloads (Gate 4 Cleared)' : 'locked';
        return redirect()->back()->with('success', "Global release for {$prospect->book_title} has been {$statusText}.");
    }
}

