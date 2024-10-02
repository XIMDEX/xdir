<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $organization;
    public $inviteLink;

    /**
     * Create a new message instance.
     */
    public function __construct(string $organization, string $inviteLink)
    {
        $this->organization = $organization;
        $this->inviteLink = $inviteLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Organization Invite Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.organizationInvite',
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
           return $this->view('emails.organizationInvite')
                       ->with([
                           'organizationName' => $this->organization,
                           'inviteLink' => $this->inviteLink,
                       ]);
    }
}
