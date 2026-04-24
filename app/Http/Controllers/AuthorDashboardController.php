<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Services\ProjectService;
use App\Services\ProspectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorDashboardController extends Controller
{
    protected $projectService;
    protected $prospectService;

    public function __construct(ProjectService $projectService, ProspectService $prospectService)
    {
        $this->projectService = $projectService;
        $this->prospectService = $prospectService;
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->projectService->getAuthorSummary($user->id, $user->email);
        return view('author.dashboard', $data);
    }

    public function enquiries()
    {
        $user = Auth::user();
        $data = $this->projectService->getAuthorDashboardData($user->id, $user->email);
        return view('author.enquiries.index', $data);
    }

    public function showEnquiry(Prospect $prospect)
    {
        $user = Auth::user();
        if (!$this->projectService->authorizeAuthor($prospect, $user->id, $user->email)) {
            abort(403);
        }

        $prospect->load('project');
        return view('author.enquiry_details', compact('prospect'));
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
        $invoices = $this->projectService->getAuthorInvoices($user->id, $user->email);
        return view('author.invoices.index', compact('invoices'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $this->projectService->getAuthorTransactions($user->id, $user->email);
        return view('author.transactions.index', compact('transactions'));
    }
}
