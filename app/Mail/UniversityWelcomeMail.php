<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UniversityWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $university;
    public string $username;
    public string $rawPassword;
    public string $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($university, string $username, string $rawPassword, ?string $loginUrl = null)
    {
        $this->university = $university;
        $this->username = $username;
        $this->rawPassword = $rawPassword;
        $this->loginUrl = $loginUrl ?? url('/login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $univName = is_object($this->university) ? $this->university->university_name : ($this->university['university_name'] ?? 'الصرح الأكاديمي');
        return new Envelope(
            subject: "منصة EduYemen • وثيقة الاعتماد الأكاديمي وبيانات حساب ({$univName})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.university_welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
