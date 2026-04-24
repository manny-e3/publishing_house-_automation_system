<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'preferred_communication' => 'array',
        'date_of_birth' => 'date',
        'default_services' => 'array',
        'quote_for_services' => 'array',
        'printing_quote' => 'array',
        'bespoke_marketing' => 'array',
        'preferred_sales_channels' => 'array',
        'agreement_terms' => 'boolean',
    ];

    public function evaluations()
    {
        return $this->hasMany(ProspectEvaluation::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
