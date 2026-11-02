<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Prospect;
use Illuminate\Http\Request;

class FinanceDashboardController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with(['prospect', 'transactions'])->latest()->paginate(15);

        $totalRevenueNGN = Transaction::where('status', 'successful')
            ->where('gateway_slug', '!=', 'stripe')
            ->sum('amount');

        $totalRevenueUSD = Transaction::where('status', 'successful')
            ->where('gateway_slug', 'stripe')
            ->sum('amount');

        $pendingInvoicesCount = Invoice::where('status', 'unpaid')->count();
        $paidInvoicesCount = Invoice::where('status', 'paid')->count();

        // Hybrid Osisi Printing Cost vs Service Revenue breakdown
        $bulkPrintEstimates = Prospect::where('payment_status', 'paid')->sum('total_bulk_print_estimate');
        $serviceFeesCollected = Prospect::where('payment_status', 'paid')->sum('total_calculated_fee');

        return view('dashboards.finance', compact(
            'invoices',
            'totalRevenueNGN',
            'totalRevenueUSD',
            'pendingInvoicesCount',
            'paidInvoicesCount',
            'bulkPrintEstimates',
            'serviceFeesCollected'
        ));
    }
}

