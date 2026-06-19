<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProjectSubmittedForReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $projectId,
        public string $projectNo,
        public string $projectTitle,
        public string $discipline
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'review_request',
            'project_id'    => $this->projectId,
            'project_no'    => $this->projectNo,
            'project_title' => $this->projectTitle,
            'discipline'    => ucfirst($this->discipline),
            'message'       => 'Review requested for ' . ucfirst($this->discipline) . ' estimate on project "' . $this->projectTitle . '"',
            'url'           => '/project/' . $this->projectId,
            'icon'          => 'fa-search',
            'color'         => '#3949ab',
        ];
    }
}
