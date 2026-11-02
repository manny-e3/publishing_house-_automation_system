<?php

namespace App\Enums;

enum DesignStatus: string
{
    case Queued = 'queued';
    case InProgress = 'in_progress';
    case CoverPendingReview = 'cover_pending_review';
    case InteriorPendingReview = 'interior_pending_review';
    case Approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::Queued => 'Queued',
            self::InProgress => 'In Progress',
            self::CoverPendingReview => 'Cover Pending Review',
            self::InteriorPendingReview => 'Interior Pending Review',
            self::Approved => 'Approved',
        };
    }
}

