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
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\AuthorAccountCreated;
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
            
            // Internal Alert: Acquisitions Team & Admins
            $recipients = User::role(['acquisitions', 'admin'])->pluck('email')->unique();
            
            if ($recipients->isNotEmpty()) {
                Mail::to($recipients)->send(new \App\Mail\NewProspectAlert($event->prospect));
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
            $prospect = $event->invoice->prospect;
            $user = User::where('email', $prospect->email)->first();
            $newAccount = false;
            $randomPassword = null;

            if (!$user) {
                $randomPassword = Str::random(10);
                $user = User::create([
                    'name' => $prospect->name,
                    'email' => $prospect->email,
                    'password' => Hash::make($randomPassword)
                ]);
                $user->assignRole('prospect');
                $newAccount = true;
            }

            // Link prospect to user
            $prospect->update(['user_id' => $user->id]);

            // 1. Notify Author of Payment Success (N-05)
            if ($event->paymentType === 'balance') {
                Mail::to($prospect->email)->send(new \App\Mail\BalancePaymentSuccessfulMail($event->invoice));
            } else {
                Mail::to($prospect->email)->send(new PaymentSuccessfulMail($event->invoice));
            }

            // 1a. Send Credentials if new account (FR-07)
            if ($newAccount) {
                Mail::to($prospect->email)->send(new AuthorAccountCreated($user, $randomPassword));
            }

            // 2. Notify Finance/Management (N-06)
            $financeRecipients = User::role(['finance', 'admin'])->pluck('email')->unique();
            if ($financeRecipients->isNotEmpty()) {
                Mail::to($financeRecipients)->send(new \App\Mail\PaymentReceiptAlert($event->invoice));
            }

            // 3. Notify Editorial Team (N-07)
            $editorialRecipients = User::role(['editorial', 'admin'])->pluck('email')->unique();
            if ($editorialRecipients->isNotEmpty()) {
                Mail::to($editorialRecipients)->send(new \App\Mail\NewProjectAlert($event->invoice));
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
            $targetRole = match($event->stage) {
                'editing', 'formatting' => 'editorial',
                'book_cover_isbn'       => 'design',
                'printing', 'distribution', 'sales_promotion' => 'logistics',
                default                 => 'admin',
            };

            // Fetch recipients for the specific role + always include admin
            $rolesToNotify = [$targetRole, 'admin'];
            $recipients = User::role($rolesToNotify)->pluck('email')->unique();

            if ($recipients->isNotEmpty()) {
                Mail::to($recipients)->send(new \App\Mail\DevelopmentStageAlert($event->project, $event->stage));
            }

        } catch (\Exception $e) {
            Log::error('Pipeline Internal Alert Failed: ' . $e->getMessage());
        }
    }
}
