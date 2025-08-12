<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNotifApproveCostEstimateToEngineer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $profile, $pdfData)
    {
        $this->project = $project;
        $this->profile = $profile;
        $this->pdfData = $pdfData;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    /** public function envelope()
    * {
        * return new Envelope(
            * subject: 'Send Notif Approv Cost Estimate To Engineer',
        * );
     * }
     * /
     * /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    /** public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }
    */

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
            ->subject('Cost Estimate Approved: Project "'.$this->project?->project_title.'"')
            ->view('emails.engineerNotification')->with([
                'project' => $this->project,
                'designEngineerName' => $this->profile->full_name,
            ])->attachData(
                $this->pdfData,
                'Cost Estimate - '.$this->project?->project_title.'.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
