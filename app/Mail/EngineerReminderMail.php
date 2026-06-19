<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EngineerReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $project, public string $engineerName, public string $discipline) {}

    public function build(): self
    {
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        return $this->from($from, $fromName)
            ->subject('Reminder: Your ' . $this->discipline . ' Estimate is Under Review — ' . $this->project->project_title)
            ->view('emails.engineerReminderNotification');
    }
}
