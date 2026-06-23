<?php

use App\Models\Project;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence channel for real-time estimate collaboration
Broadcast::channel('presence-estimate.{projectId}', function ($user, $projectId) {
    $project = Project::find($projectId);
    if (!$project) return false;

    $position   = $user->profiles?->position ?? '';
    $discipline = explode('_', $position)[1] ?? null;
    $name       = $user->profiles?->full_name ?? $user->name;

    return [
        'id'         => $user->id,
        'name'       => $name,
        'initials'   => strtoupper(mb_substr($name, 0, 2)),
        'discipline' => $discipline,
        'position'   => $position,
    ];
});
