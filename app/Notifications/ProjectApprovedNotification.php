<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProjectApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $projectId,
        public string $projectNo,
        public string $projectTitle
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'project_approved',
            'project_id'    => $this->projectId,
            'project_no'    => $this->projectNo,
            'project_title' => $this->projectTitle,
            'message'       => 'Project "' . $this->projectTitle . '" has been fully approved.',
            'url'           => '/project/' . $this->projectId,
            'icon'          => 'fa-check-circle',
            'color'         => '#2e7d32',
        ];
    }
}
