<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisciplinePublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $project,
        public string $engineerName,
        public string $discipline,
        public string $publishedByName,
        public array $disciplineStatuses = []
    ) {}

    public function build(): self
    {
        $from = getenv('MAIL_FROM_ADDRESS');
        $fromName = getenv('MAIL_FROM_NAME');

        return $this->from($from, $fromName)
            ->subject('Discipline Published: ' . $this->discipline . ' — ' . $this->project->project_title)
            ->view('emails.disciplinePublishedNotification');
    }
}
