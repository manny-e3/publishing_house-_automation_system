<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch matching enquiries/prospects
        // Note: For existing enquiries without user_id, we might want to match by email
        // but for now, we only show those explicitly linked.
        $enquiries = Prospect::with('project')
            ->where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->get();

        // Ensure user_id is set for any matching by email that isn't linked yet
        foreach ($enquiries as $enquiry) {
            if (!$enquiry->user_id) {
                $enquiry->update(['user_id' => $user->id]);
            }
        }

        return view('author.dashboard', compact('enquiries'));
    }

    public function showEnquiry(Prospect $prospect)
    {
        $this->authorizeAuthor($prospect);
        $prospect->load('project');
        return view('author.enquiry_details', compact('prospect'));
    }

    private function authorizeAuthor(Prospect $prospect)
    {
        if ($prospect->user_id !== Auth::id() && $prospect->email !== Auth::user()->email) {
            abort(403);
        }
    }
    
    public function createEnquiry()
    {
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        $groupedRates = \App\Models\PricingRate::all()->groupBy('category');
        return view('author.enquiries.create', compact('rates', 'groupedRates'));
    }

    public function invoices()
    {
        $user = Auth::user();
        $invoices = \App\Models\Invoice::whereHas('prospect', function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('email', $user->email);
        })->with('prospect')->latest()->get();

        return view('author.invoices.index', compact('invoices'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = \App\Models\Transaction::whereHas('invoice.prospect', function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('email', $user->email);
        })->with('invoice.prospect')->latest()->get();

        return view('author.transactions.index', compact('transactions'));
    }
}
