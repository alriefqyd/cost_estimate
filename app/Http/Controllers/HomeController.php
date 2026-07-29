<?php

namespace App\Http\Controllers;

use App\Models\EquipmentTools;
use App\Models\ManPower;
use App\Models\Material;
use App\Models\Project;
use App\Models\WorkItem;

class HomeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user   = auth()->user();

        // Shared project scope based on the user's role
        $projectScope = function ($q) use ($userId, $user) {
            if ($user->isViewAllCostEstimateRole()) return;
            $q->where(function ($inner) use ($userId, $user) {
                $inner->where('created_by', $userId);

                if ($user->isAssigneeCostEstimateRole()) {
                    $inner->orWhere('design_engineer_mechanical', $userId)
                          ->orWhere('design_engineer_civil', $userId)
                          ->orWhere('design_engineer_electrical', $userId)
                          ->orWhere('design_engineer_instrument', $userId)
                          ->orWhere('design_engineer_architect', $userId)
                          ->orWhere('design_engineer_it', $userId)
                          ->orWhere('project_manager', $userId)
                          ->orWhere('project_engineer', $userId);
                }

                if ($user->isAllElectricalCostEstimateRole())  $inner->orWhereNotNull('design_engineer_electrical');
                if ($user->isAllInstrumentCostEstimateRole())  $inner->orWhereNotNull('design_engineer_instrument');
                if ($user->isAllMechanicalCostEstimateRole())  $inner->orWhereNotNull('design_engineer_mechanical');
                if ($user->isAllCivilCostEstimateRole())       $inner->orWhereNotNull('design_engineer_civil');
            });
        };

        $projectTotal    = Project::when(true, $projectScope)->count();
        $projectDraft    = Project::when(true, $projectScope)->where('status', Project::DRAFT)->count();
        $projectApproved = Project::when(true, $projectScope)->where('status', Project::APPROVE)->count();

        $workItemTotal    = WorkItem::count();
        $workItemDraft    = WorkItem::where('status', WorkItem::DRAFT)->count();
        $workItemReviewed = WorkItem::where('status', WorkItem::REVIEWED)->count();

        $manPowerTotal    = ManPower::count();
        $manPowerDraft    = ManPower::where('status', ManPower::DRAFT)->count();
        $manPowerReviewed = ManPower::where('status', ManPower::REVIEWED)->count();

        $materialTotal    = Material::count();
        $materialDraft    = Material::where('status', Material::DRAFT)->count();
        $materialReviewed = Material::where('status', Material::REVIEWED)->count();

        $equipmentTotal    = EquipmentTools::count();
        $equipmentDraft    = EquipmentTools::where('status', EquipmentTools::DRAFT)->count();
        $equipmentReviewed = EquipmentTools::where('status', EquipmentTools::REVIEWED)->count();

        $recentProjects = Project::when(true, $projectScope)
            ->latest()
            ->limit(6)
            ->get(['id', 'project_no', 'project_title', 'status', 'created_at']);

        $reviewQueues = [];

        $myPendingProjects = Project::where(function ($q) use ($userId) {
            $q->where(function ($s) use ($userId) {
                $s->where('civil_approver', $userId)->where('civil_approval_status', Project::PENDING);
            })->orWhere(function ($s) use ($userId) {
                $s->where('mechanical_approver', $userId)->where('mechanical_approval_status', Project::PENDING);
            })->orWhere(function ($s) use ($userId) {
                $s->where('electrical_approver', $userId)->where('electrical_approval_status', Project::PENDING);
            })->orWhere(function ($s) use ($userId) {
                $s->where('instrument_approver', $userId)->where('instrument_approval_status', Project::PENDING);
            })->orWhere(function ($s) use ($userId) {
                $s->where('it_approver', $userId)->where('it_approval_status', Project::PENDING);
            })->orWhere(function ($s) use ($userId) {
                $s->where('architect_approver', $userId)->where('architect_approval_status', Project::PENDING);
            });
        });

        $myPendingProjectsCount = (clone $myPendingProjects)->count();

        if ($myPendingProjectsCount > 0) {
            $reviewQueues['costEstimate'] = [
                'label' => 'Cost Estimate',
                'icon'  => 'fa-folder-open',
                'color' => '#2e75b6',
                'bg'    => '#e8f0fb',
                'count' => $myPendingProjectsCount,
                'url'   => '/project?my_reviews=1',
                'items' => (clone $myPendingProjects)->latest()->limit(3)->get()
                    ->map(fn ($item) => [
                        'id' => $item->id, 'code' => $item->project_no,
                        'label' => $item->project_title, 'url' => '/project/' . $item->id,
                    ]),
            ];
        }

        if ($user->isMaterialReviewerRole() && $materialDraft > 0) {
            $reviewQueues['material'] = [
                'label' => 'Materials',
                'icon'  => 'fa-truck',
                'color' => '#b8860b',
                'bg'    => '#fff8e1',
                'count' => $materialDraft,
                'url'   => '/material?status=' . Material::DRAFT,
                'items' => Material::where('status', Material::DRAFT)
                    ->latest()->limit(3)->get()
                    ->map(fn ($item) => [
                        'id' => $item->id, 'code' => $item->code,
                        'label' => $item->tool_equipment_description, 'url' => '/material/' . $item->id,
                    ]),
            ];
        }

        if ($user->isWorkItemReviewer() && $workItemDraft > 0) {
            $reviewQueues['workItem'] = [
                'label' => 'Work Items',
                'icon'  => 'fa-briefcase',
                'color' => '#ED7D31',
                'bg'    => '#fdeee3',
                'count' => $workItemDraft,
                'url'   => '/work-item?status=' . WorkItem::DRAFT,
                'items' => WorkItem::where('status', WorkItem::DRAFT)
                    ->latest()->limit(3)->get()
                    ->map(fn ($item) => [
                        'id' => $item->id, 'code' => $item->code,
                        'label' => $item->description, 'url' => '/work-item/' . $item->id,
                    ]),
            ];
        }

        if ($user->isToolsEquipmentReviewerRole() && $equipmentDraft > 0) {
            $reviewQueues['equipmentTools'] = [
                'label' => 'Tools & Equipment',
                'icon'  => 'fa-wrench',
                'color' => '#7030A0',
                'bg'    => '#f3e8fb',
                'count' => $equipmentDraft,
                'url'   => '/tool-equipment?status=' . EquipmentTools::DRAFT,
                'items' => EquipmentTools::where('status', EquipmentTools::DRAFT)
                    ->latest()->limit(3)->get()
                    ->map(fn ($item) => [
                        'id' => $item->id, 'code' => $item->code,
                        'label' => $item->description, 'url' => '/tool-equipment/' . $item->id,
                    ]),
            ];
        }

        if ($user->isManPowerReviewer() && $manPowerDraft > 0) {
            $reviewQueues['manPower'] = [
                'label' => 'Man Power',
                'icon'  => 'fa-users',
                'color' => '#548235',
                'bg'    => '#e6f2df',
                'count' => $manPowerDraft,
                'url'   => '/man-power?status=' . ManPower::DRAFT,
                'items' => ManPower::where('status', ManPower::DRAFT)
                    ->latest()->limit(3)->get()
                    ->map(fn ($item) => [
                        'id' => $item->id, 'code' => $item->code,
                        'label' => $item->title, 'url' => '/man-power/' . $item->id,
                    ]),
            ];
        }

        return view('home.index', compact(
            'projectTotal', 'projectDraft', 'projectApproved',
            'workItemTotal', 'workItemDraft', 'workItemReviewed',
            'manPowerTotal', 'manPowerDraft', 'manPowerReviewed',
            'materialTotal', 'materialDraft', 'materialReviewed',
            'equipmentTotal', 'equipmentDraft', 'equipmentReviewed',
            'recentProjects', 'reviewQueues'
        ));
    }

    public function guide()
    {
        return view('home.guide');
    }
}
