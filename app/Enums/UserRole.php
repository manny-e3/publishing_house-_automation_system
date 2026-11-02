<?php

namespace App\Enums;

enum UserRole: string
{
    case AuthorAgent = 'author_agent';
    case AcquisitionAdmin = 'acquisition_admin';
    case Editorial = 'editorial';
    case Finance = 'finance';
    case Design = 'design';
    case Marketing = 'marketing';
    case It = 'it';
    case PrintDistribution = 'print_distribution';

    public function label(): string
    {
        return match ($this) {
            self::AuthorAgent => 'Author / Agent',
            self::AcquisitionAdmin => 'Acquisitions Admin',
            self::Editorial => 'Editorial Team',
            self::Finance => 'Finance Team',
            self::Design => 'Design & Layout',
            self::Marketing => 'Marketing & PR',
            self::It => 'IT & Infrastructure',
            self::PrintDistribution => 'Print & Distribution',
        };
    }
}

