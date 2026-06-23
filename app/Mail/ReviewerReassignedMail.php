<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewerReassignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $discipline;
    public $newReviewerName;

    public function __construct($project, string $discipline, string $newReviewerName)
    {
        $this->project = $project;
        $this->discipline = $discipline;
        $this->newReviewerName = $newReviewerName;
    }

    public function build()
    {
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        return $this->from($from, $fromName)
            ->subject('Reviewer Assignment Updated: Project "' . $this->project?->project_title . '"')
            ->view('emails.reviewerReassignedNotification')->with([
                'project'         => $this->project,
                'discipline'      => $this->discipline,
                'newReviewerName' => $this->newReviewerName,
            ]);
    }
}
