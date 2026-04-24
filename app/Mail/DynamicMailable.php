<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class DynamicMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $template;
    public $data;
    public $body;

    public function __construct($templateKey, $data = [])
    {
        $this->template = EmailTemplate::where('key', $templateKey)->first();
        $this->data = $data;

        if ($this->template) {
            $this->body = $this->parseTemplate($this->template->body, $data);
        }
    }

    public function build()
    {
        if (!$this->template) {
            return $this->subject('Untitled Notification')->html('<p>No template found.</p>');
        }

        $subject = $this->parseTemplate($this->template->subject, $this->data);

        return $this->subject($subject)
                    ->view('emails.dynamic')
                    ->with([
                        'body' => $this->body
                    ]);
    }

    protected function parseTemplate($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        return $content;
    }
}
