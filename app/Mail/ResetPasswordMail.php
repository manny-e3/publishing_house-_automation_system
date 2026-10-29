<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct(User $user, string $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        $template = EmailTemplate::where('key', 'password_reset')->first();

        if ($template) {
            $subject = str_replace(
                ['{user_name}', '{reset_url}'],
                [$this->user->name, $this->resetUrl],
                $template->subject
            );
            $body = str_replace(
                ['{user_name}', '{reset_url}'],
                [$this->user->name, $this->resetUrl],
                $template->body
            );

            return $this->subject($subject)
                        ->view('emails.dynamic')
                        ->with(['body' => $body]);
        }

        return $this->subject('Reset Your Password - The Curated Archive')
                    ->view('emails.reset_password')
                    ->with([
                        'user' => $this->user,
                        'resetUrl' => $this->resetUrl,
                    ]);
    }
}

