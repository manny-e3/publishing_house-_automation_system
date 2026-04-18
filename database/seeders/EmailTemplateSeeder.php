<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'key' => 'prospect_submitted',
                'name' => 'Manuscript Enquiry Received',
                'subject' => 'We have received your manuscript enquiry: {book_title}',
                'body' => '<p>Dear {author_name},</p><p>Thank you for submitting your manuscript excerpt for <strong>{book_title}</strong>. Our Acquisitions team is currently reviewing your submission.</p><p>You will receive an update once a decision has been reached.</p>',
                'placeholders' => ['author_name', 'book_title']
            ],
            [
                'key' => 'manuscript_accepted',
                'name' => 'Manuscript Accepted',
                'subject' => 'Congratulations! Your manuscript has been accepted',
                'body' => '<p>Dear {author_name},</p><p>We are pleased to inform you that your manuscript <strong>{book_title}</strong> has been accepted for publication.</p><p>Attached is your invoice to proceed with the first installment.</p>',
                'placeholders' => ['author_name', 'book_title']
            ],
            [
                'key' => 'manuscript_rejected',
                'name' => 'Manuscript Rejected',
                'subject' => 'Status Update: {book_title}',
                'body' => '<p>Dear {author_name},</p><p>Thank you for sharing your work with us. After careful review, we regret to inform you that we will not be proceeding with <strong>{book_title}</strong> at this time.</p><p>We wish you the best in your writing journey.</p>',
                'placeholders' => ['author_name', 'book_title']
            ],
            [
                'key' => 'otp_verification',
                'name' => 'OTP Verification Code',
                'subject' => '{otp} is your verification code',
                'body' => '<p>Your secure verification code is:</p><h2 style="font-size: 32px; color: #001f3f;">{otp}</h2><p>This code expires in 10 minutes.</p>',
                'placeholders' => ['otp']
            ]
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
