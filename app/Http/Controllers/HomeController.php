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

        return view('home.index', compact(
            'projectTotal', 'projectDraft', 'projectApproved',
            'workItemTotal', 'workItemDraft', 'workItemReviewed',
            'manPowerTotal', 'manPowerDraft', 'manPowerReviewed',
            'materialTotal', 'materialDraft', 'materialReviewed',
            'equipmentTotal', 'equipmentDraft', 'equipmentReviewed',
            'recentProjects'
        ));
    }

    public function guide()
    {
        return view('home.guide');
    }
}
