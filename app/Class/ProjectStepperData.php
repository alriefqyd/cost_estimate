<?php

namespace App\Class;

use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectStepperData
{
    public string $stepWbs;
    public string $stepEstimate;
    public string $stepApproval;
    public int    $wbsCount;
    public string $estimateSublabel;
    public string $approvalSublabel;

    private function __construct() {}

    public static function build(Project $project, Collection|array $wbs): self
    {
        $statuses           = collect(json_decode($project->estimate_discipline_status ?? '[]'));
        $totalDisciplines   = $statuses->count();
        $publishedCount     = $statuses->where('status', 'PUBLISH')->count();
        $wbsCount           = count($wbs);

        $wbsDone      = $wbsCount > 0;
        $estimateDone = $totalDisciplines > 0 && $publishedCount === $totalDisciplines;
        $approved     = $project->status === Project::APPROVE;
        $waiting      = in_array($project->status, [
            Project::WAITING_FOR_APPROVAL,
            Project::APPROVE_BY_DISCIPLINE_REVIEWER,
            Project::PENDING_DISCIPLINE_APPROVAL,
        ]);

        $self = new self();
        $self->wbsCount        = $wbsCount;
        $self->stepWbs         = $wbsDone      ? 'complete' : 'active';
        $self->stepEstimate    = $estimateDone ? 'complete' : ($wbsDone ? 'active' : 'pending');
        $self->stepApproval    = $approved     ? 'complete' : ($estimateDone || $waiting ? 'active' : 'pending');
        $self->estimateSublabel = $totalDisciplines > 0
            ? "{$publishedCount} / {$totalDisciplines} disciplines published"
            : 'Not started';
        $self->approvalSublabel = $approved  ? 'Approved'
            : ($waiting                      ? 'Waiting for review'
            : 'Not started');

        return $self;
    }
}
