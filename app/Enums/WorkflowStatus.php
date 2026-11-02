<?php

namespace App\Enums;

enum WorkflowStatus: string
{
    case LeadCaptured = 'lead_captured';
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case ContractPending = 'contract_pending';
    case InEditing = 'in_editing';
    case InDesign = 'in_design';
    case ReadyForPrint = 'ready_for_print';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::LeadCaptured => 'Lead Captured',
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under Review',
            self::ContractPending => 'Contract Pending',
            self::InEditing => 'In Editing',
            self::InDesign => 'In Design',
            self::ReadyForPrint => 'Ready for Print',
            self::Published => 'Published',
        };
    }
}

