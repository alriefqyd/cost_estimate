<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifApproveCostEstimateToEngineer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct($project, $profile, $attachmentData)
    {
        $this->project = $project;
        $this->profile = $profile;
        $this->attachmentData = $attachmentData;
    }

    public function attachments()
    {
        return [];
    }

    public function build()
    {
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        $mail = $this->from($from, $fromName)
            ->subject('Cost Estimate Approved: Project "' . $this->project?->project_title . '"')
            ->view('emails.engineerNotification')->with([
                'project' => $this->project,
                'designEngineerName' => $this->profile->full_name,
            ]);

        if ($this->attachmentData) {
            $mail->attachData(
                $this->attachmentData,
                'Cost Estimate - ' . $this->project?->project_title . '.xlsx',
                [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }

        return $mail;
    }
}
