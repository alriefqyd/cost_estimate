<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
//    public function envelope()
//    {
//        return new Envelope(
//            subject: 'Send Mail',
//        );
//    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
//    public function content()
//    {
//        return new Content(
//            view: 'emails.approverNotification',
//        );
//    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build(){
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        return $this->from($from,$fromName)
            ->subject('Cost Estimate Review Required: Project "'.$this->project?->project_title.'"')
            ->view('emails.approverNotification')->with([
                'project' => $this->project
            ]);
    }
}
