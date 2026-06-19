<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewNoteNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public $project,
        public $engineerName,
        public $reviewerName,
        public $discipline,
        public $noteText
    ) {}

    public function build()
    {
        return $this
            ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))
            ->subject('New Review Note on Project "' . $this->project->project_title . '"')
            ->view('emails.reviewNoteNotification');
    }
}
