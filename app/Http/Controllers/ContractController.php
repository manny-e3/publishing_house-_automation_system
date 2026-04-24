<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Display the contract signing view for the author.
     */
    public function show(Contract $contract)
    {
        $prospect = $contract->prospect ?? $contract->project->prospect;

        if ($prospect->email !== auth()->user()->email) {
            abort(403, 'Unauthorized access to this contract.');
        }

        return view('author.contracts.show', compact('contract'));
    }

    /**
     * Handle the signature submission.
     */
    public function sign(Request $request, Contract $contract)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'agree' => 'accepted'
        ]);

        $this->contractService->signContract($contract, [
            'name' => $request->name
        ]);

        return redirect()->route('author.dashboard')
            ->with('success', 'Contract signed successfully. Your project is now moving to production.');
    }
}
