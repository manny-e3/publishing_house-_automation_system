<?php

namespace App\Listeners;

use App\Events\ProspectSubmitted;
use App\Events\InvoiceGenerated;
use App\Events\ManuscriptAccepted;
use App\Events\ManuscriptRejected;
use App\Events\PaymentSuccessful;
use App\Events\ProjectStageUpdated;
use App\Mail\ProjectStatusUpdated;
use App\Mail\AuthorAcknowledgment;
use App\Mail\ManuscriptAccepted as ManuscriptAcceptedMail;
use App\Mail\ManuscriptRejected as ManuscriptRejectedMail;
use App\Mail\PaymentSuccessfulMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAuthorNotifications
{
    /**
     * Handle the events.
     */
    public function handle($event): void
    {
        if ($event instanceof ProspectSubmitted) {
            $this->handleProspectSubmitted($event);
        } elseif ($event instanceof InvoiceGenerated) {
            $this->handleInvoiceGenerated($event);
        } elseif ($event instanceof ManuscriptRejected) {
            $this->handleManuscriptRejected($event);
        } elseif ($event instanceof PaymentSuccessful) {
            $this->handlePaymentSuccessful($event);
        } elseif ($event instanceof ProjectStageUpdated) {
            $this->handleProjectStageUpdated($event);
        }
    }

    private function handleProspectSubmitted($event)
    {
        try {
            // Author Acknowledgment
            Mail::to($event->prospect->email)->send(new AuthorAcknowledgment($event->prospect));
            
            // Acquisitions Team Alert (N-02)
            $acquisitionsEmail = \App\Models\Setting::where('key', 'acquisitions_email')->value('value') 
                ?? \App\Models\Setting::where('key', 'support_email')->value('value') 
                ?? config('mail.from.address');
            
            if ($acquisitionsEmail) {
                Mail::to($acquisitionsEmail)->send(new \App\Mail\NewProspectAlert($event->prospect));
            }
        } catch (\Exception $e) {
            Log::error('Prospect Notification Failed: ' . $e->getMessage());
        }
    }

    private function handleInvoiceGenerated($event)
    {
        try {
            Mail::to($event->invoice->prospect->email)->send(new ManuscriptAcceptedMail($event->invoice));
        } catch (\Exception $e) {
            Log::error('Acceptance (N-04) Email Failed: ' . $e->getMessage());
        }
    }

    private function handleManuscriptRejected($event)
    {
        try {
            Mail::to($event->prospect->email)->send(new ManuscriptRejectedMail($event->prospect));
        } catch (\Exception $e) {
            Log::error('Rejection Email Failed: ' . $e->getMessage());
        }
    }

    private function handlePaymentSuccessful($event)
    {
        try {
            // 1. Notify Author (N-05)
            Mail::to($event->invoice->prospect->email)->send(new PaymentSuccessfulMail($event->invoice));

            // 2. Notify Finance/Management (N-06)
            $financeEmail = \App\Models\Setting::where('key', 'finance_email')->value('value') 
                ?? \App\Models\Setting::where('key', 'support_email')->value('value')
                ?? config('mail.from.address');
            
            if ($financeEmail) {
                Mail::to($financeEmail)->send(new \App\Mail\PaymentReceiptAlert($event->invoice));
            }

            // 3. Notify Editorial Team (N-07)
            $editorialEmail = \App\Models\Setting::where('key', 'editorial_email')->value('value') 
                ?? \App\Models\Setting::where('key', 'support_email')->value('value')
                ?? config('mail.from.address');

            if ($editorialEmail) {
                Mail::to($editorialEmail)->send(new \App\Mail\NewProjectAlert($event->invoice));
            }

        } catch (\Exception $e) {
            Log::error('Payment Success Internal Notifications Failed: ' . $e->getMessage());
        }
    }

    private function handleProjectStageUpdated($event)
    {
        try {
            // 1. Notify Author
            Mail::to($event->project->prospect->email)->send(new ProjectStatusUpdated($event->project, $event->stage));

            // 2. Route Internal Alert (N-08 to N-12)
            $teamKey = match($event->stage) {
                'editing', 'formatting' => 'editorial_email',
                'cover_design'          => 'design_email',
                'printing', 'distribution', 'completed' => 'logistics_email',
                default                 => 'support_email',
            };

            $teamEmail = \App\Models\Setting::where('key', $teamKey)->value('value')
                ?? \App\Models\Setting::where('key', 'support_email')->value('value')
                ?? config('mail.from.address');

            if ($teamEmail) {
                Mail::to($teamEmail)->send(new \App\Mail\DevelopmentStageAlert($event->project, $event->stage));
            }

        } catch (\Exception $e) {
            Log::error('Pipeline Internal Alert Failed: ' . $e->getMessage());
        }
    }
}
