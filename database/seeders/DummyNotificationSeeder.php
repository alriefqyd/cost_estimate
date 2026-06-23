<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\ProjectApprovedNotification;
use App\Notifications\ProjectSubmittedForReviewNotification;
use App\Notifications\ReviewNoteAddedNotification;
use Illuminate\Database\Seeder;

class DummyNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Seed for ALL users so whoever logs in sees demo notifications
        $users = User::all();

        foreach ($users as $user) {
            // Clear existing dummy notifications first
            $user->notifications()->delete();

            // 1. Unread — review request (most recent)
            $user->notify(new ProjectSubmittedForReviewNotification(
                projectId:    1,
                projectNo:    'EPS-2025-001',
                projectTitle: 'Piping Replacement — Smelter Area',
                discipline:   'mechanical'
            ));

            // 2. Unread — review note from a reviewer
            $user->notify(new ReviewNoteAddedNotification(
                projectId:    1,
                projectNo:    'EPS-2025-001',
                projectTitle: 'Piping Replacement — Smelter Area',
                discipline:   'civil',
                reviewerName: 'Budi Santoso'
            ));

            // 3. Read — project fully approved (older)
            $notif = tap($user->notifications()->create([
                'id'         => \Illuminate\Support\Str::uuid(),
                'type'       => ProjectApprovedNotification::class,
                'data'       => json_encode([
                    'type'          => 'project_approved',
                    'project_id'    => 2,
                    'project_no'    => 'EPS-2024-088',
                    'project_title' => 'Electrical Panel Upgrade — Concentrator',
                    'message'       => 'Project "Electrical Panel Upgrade — Concentrator" has been fully approved.',
                    'url'           => '/project/2',
                    'icon'          => 'fa-check-circle',
                    'color'         => '#2e7d32',
                ]),
                'read_at'    => now()->subHours(2),
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(2),
            ]));

            // 4. Read — another review request (older, already read)
            $user->notifications()->create([
                'id'         => \Illuminate\Support\Str::uuid(),
                'type'       => ProjectSubmittedForReviewNotification::class,
                'data'       => json_encode([
                    'type'          => 'review_request',
                    'project_id'    => 3,
                    'project_no'    => 'EPS-2024-075',
                    'project_title' => 'Instrument Loop Check — Furnace',
                    'discipline'    => 'Instrument',
                    'message'       => 'Review requested for Instrument estimate on project "Instrument Loop Check — Furnace"',
                    'url'           => '/project/3',
                    'icon'          => 'fa-search',
                    'color'         => '#3949ab',
                ]),
                'read_at'    => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]);
        }

        $this->command->info('Dummy notifications seeded for ' . $users->count() . ' user(s). 2 unread, 2 read per user.');
    }
}
