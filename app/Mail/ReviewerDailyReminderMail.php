<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewerDailyReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $reviewerName,
        public array $pendingProjects
    ) {}

    public function build(): self
    {
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        return $this->from($from, $fromName)
            ->subject('Daily Reminder: Projects Pending Your Review')
            ->view('emails.reviewerDailyReminder');
    }
}
