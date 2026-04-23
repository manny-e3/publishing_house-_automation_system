<?php

namespace App\Events;

use App\Models\Invoice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;
    public $paymentType;

    public function __construct(Invoice $invoice, string $paymentType = 'initial')
    {
        $this->invoice = $invoice;
        $this->paymentType = $paymentType;
    }
}
