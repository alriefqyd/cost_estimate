<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewNoteAddedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $projectId,
        public string $projectNo,
        public string $projectTitle,
        public string $discipline,
        public string $reviewerName
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'review_note',
            'project_id'    => $this->projectId,
            'project_no'    => $this->projectNo,
            'project_title' => $this->projectTitle,
            'discipline'    => ucfirst($this->discipline),
            'reviewer_name' => $this->reviewerName,
            'message'       => $this->reviewerName . ' added a review note on project "' . $this->projectTitle . '"',
            'url'           => '/project/' . $this->projectId,
            'icon'          => 'fa-comment',
            'color'         => '#e65c00',
        ];
    }
}
