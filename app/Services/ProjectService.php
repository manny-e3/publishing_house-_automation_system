<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Prospect;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Events\ProjectStageUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    /**
     * Get paginated projects for admin.
     */
    public function getProjects(int $perPage = 10): LengthAwarePaginator
    {
        return Project::with('prospect')->latest()->paginate($perPage);
    }

    /**
     * Update project stage and fire events.
     */
    public function updateStage(Project $project, string $stage): void
    {
        $project->update([
            'current_stage' => $stage
        ]);

        event(new ProjectStageUpdated($project, $stage));
    }

    /**
     * Get data for the author dashboard.
     */
    public function getAuthorDashboardData(int $userId, string $email): array
    {
        // Fetch matching enquiries/prospects
        $enquiries = Prospect::with('project')
            ->where('user_id', $userId)
            ->orWhere('email', $email)
            ->latest()
            ->get();

        // Ensure user_id is set for any matching by email that isn't linked yet
        foreach ($enquiries as $enquiry) {
            if (!$enquiry->user_id) {
                $enquiry->update(['user_id' => $userId]);
            }
        }

        return [
            'enquiries' => $enquiries
        ];
    }

    /**
     * Get author invoices.
     */
    public function getAuthorInvoices(int $userId, string $email): Collection
    {
        return Invoice::whereHas('prospect', function ($query) use ($userId, $email) {
            $query->where('user_id', $userId)->orWhere('email', $email);
        })->with('prospect')->latest()->get();
    }

    /**
     * Get author transactions.
     */
    public function getAuthorTransactions(int $userId, string $email): Collection
    {
        return Transaction::whereHas('invoice.prospect', function ($query) use ($userId, $email) {
            $query->where('user_id', $userId)->orWhere('email', $email);
        })->with('invoice.prospect')->latest()->get();
    }

    /**
     * Authorize that the user owns the prospect/project.
     */
    public function authorizeAuthor(Prospect $prospect, int $userId, string $email): bool
    {
        return $prospect->user_id === $userId || $prospect->email === $email;
    }
}
