<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Project;
use App\Models\Prospect;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractService
{
    /**
     * Generate a new publishing contract PDF for a project.
     */
    public function generateContract(Project $project): Contract
    {
        // 1. Prepare Data
        $data = [
            'project' => $project,
            'author' => $project->prospect->name,
            'book_title' => $project->prospect->book_title,
            'date' => now()->format('F d, Y'),
        ];

        // 2. Generate PDF
        $pdf = Pdf::loadView('pdf.contract', $data);
        
        // 3. Save PDF
        $filename = 'publishing_contract_' . $project->id . '_' . time() . '.pdf';
        $path = 'contracts/' . $filename;
        Storage::disk('local')->put($path, $pdf->output());

        // 4. Create record
        return Contract::create([
            'project_id' => $project->id,
            'prospect_id' => $project->prospect_id,
            'contract_type' => 'publishing',
            'status' => 'sent',
            'document_path' => $path,
        ]);
    }

    /**
     * Generate and automatically sign a Submission Agreement for a prospect.
     */
    public function generateSubmissionContract(Prospect $prospect, array $signatureData): Contract
    {
        // 1. Prepare Data
        $data = [
            'prospect' => $prospect,
            'author' => $prospect->name,
            'book_title' => $prospect->book_title,
            'date' => now()->format('F d, Y'),
            'signature_name' => $signatureData['name'],
            'ip' => $signatureData['ip'],
        ];

        // 2. Generate PDF (using a separate template for submission terms)
        $pdf = Pdf::loadView('pdf.submission_agreement', $data);
        
        // 3. Save PDF
        $filename = 'submission_agreement_' . $prospect->id . '_' . time() . '.pdf';
        $path = 'contracts/' . $filename;
        Storage::disk('local')->put($path, $pdf->output());

        // 4. Create and auto-sign the record
        return Contract::create([
            'prospect_id' => $prospect->id,
            'contract_type' => 'submission',
            'status' => 'signed',
            'signed_at' => now(),
            'signature_info' => [
                'name' => $signatureData['name'],
                'ip' => $signatureData['ip'],
                'user_agent' => request()->userAgent(),
            ],
            'document_path' => $path,
        ]);
    }

    /**
     * Record the signature for a contract.
     */
    public function signContract(Contract $contract, array $signatureData): void
    {
        $contract->update([
            'status' => 'signed',
            'signed_at' => now(),
            'signature_info' => [
                'name' => $signatureData['name'],
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);
    }
}
