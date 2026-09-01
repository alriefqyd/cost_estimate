<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DisciplinePublishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $projectId,
        public string $projectNo,
        public string $projectTitle,
        public string $discipline,
        public string $publishedByName
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'              => 'discipline_published',
            'project_id'        => $this->projectId,
            'project_no'        => $this->projectNo,
            'project_title'     => $this->projectTitle,
            'discipline'        => ucfirst($this->discipline),
            'published_by_name' => $this->publishedByName,
            'message'           => ucfirst($this->discipline) . ' estimate published by ' . $this->publishedByName . ' on project "' . $this->projectTitle . '"',
            'url'               => '/project/' . $this->projectId,
            'icon'              => 'fa-paper-plane',
            'color'             => '#00838f',
        ];
    }
}
