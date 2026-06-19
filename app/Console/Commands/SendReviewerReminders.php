<?php

namespace App\Console\Commands;

use App\Services\ProjectServices;
use Illuminate\Console\Command;

class SendReviewerReminders extends Command
{
    protected $signature = 'app:send-reviewer-reminders';
    protected $description = 'Send daily reminder emails to reviewers with pending projects';

    public function handle(): int
    {
        (new ProjectServices())->sendEmailRemainderToReviewer();
        $this->info('Reviewer reminder emails sent.');
        return Command::SUCCESS;
    }
}
