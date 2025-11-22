<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlumniEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $emailSubject,
        public string $message
    ) {
        // Replace {{name}} placeholder with actual user name
        $this->message = str_replace('{{name}}', $this->user->name, $this->message);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.alumni-custom',
            with: [
                'user' => $this->user,
                'emailBody' => $this->message,
                'emailSubject' => $this->emailSubject,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
