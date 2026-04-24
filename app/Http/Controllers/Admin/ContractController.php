<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    /**
     * Display a listing of all contracts.
     */
    public function index()
    {
        $contracts = Contract::with(['prospect', 'project'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contracts.index', compact('contracts'));
    }

    /**
     * Download the contract PDF.
     */
    public function download(Contract $contract)
    {
        if (!Storage::disk('local')->exists($contract->document_path)) {
            return back()->with('error', 'Contract file not found.');
        }

        return Storage::disk('local')->download($contract->document_path);
    }
}
