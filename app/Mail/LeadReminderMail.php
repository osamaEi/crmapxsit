<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly object $reminder,
        public readonly object $lead,
        public readonly object $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder: Lead "'.$this->lead->title.'"',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin::emails.lead-reminder',
        );
    }
}
