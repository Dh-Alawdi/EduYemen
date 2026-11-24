<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $type; // 'login' or 'register'
    public ?string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $type = 'login', ?string $userName = null)
    {
        $this->code = $code;
        $this->type = $type;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'register' 
            ? "رمز تأكيد البريد الإلكتروني وإنشاء الحساب في منصة EduYemen"
            : "رمز التحقق السريع لتسجيل الدخول في منصة EduYemen";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auth_otp',
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
