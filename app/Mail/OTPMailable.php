<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        $template = \App\Models\EmailTemplate::where('key', 'otp_verification')->first();
        
        if ($template) {
            $subject = str_replace('{otp}', $this->otp, $template->subject);
            $body = str_replace('{otp}', $this->otp, $template->body);

            return $this->subject($subject)
                        ->view('emails.dynamic')
                        ->with(['body' => $body]);
        }

        return $this->subject('Verify Your Login - The Curated Archive')
                    ->view('emails.otp');
    }
}
