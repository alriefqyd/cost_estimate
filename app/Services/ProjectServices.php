<?php

namespace App\Services;

use App\Class\ProjectClass;
use App\Class\ProjectTotalCostClass;
use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProjectServices
{
    public function getProjectsData(Request $request){
        $order = $request->order;
        $sort =  $request->sort;

        $requestFilter = request(['q','status','civil','mechanical','electrical','instrument','sponsor']);

        $projects = Project::with(['designEngineerMechanical.profiles','designEngineerCivil.profiles','designEngineerElectrical.profiles','designEngineerInstrument.profiles','projectArea','projectManager.profiles'])
            ->when(!auth()->user()->isViewAllCostEstimateRole(), function ($subQuery){
                return $subQuery->access();
            });

        $countDraft = clone $projects;
        $countApprove = clone $projects;

        $projectList = $projects->filter($requestFilter, true)->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();
        $countDraft = $countDraft->filter($requestFilter,false)->where('status',Project::DRAFT)->count();
        $countApprove = $countApprove->filter($requestFilter,false)->where('status',Project::APPROVE)->count();

        return [
            'projectList' => $projectList,
            'draft' => $countDraft,
            'approve' => $countApprove
        ];
    }
    /**
     * Get all data estimate discipline by project id
     * @param Project $project
     * @param Request $request
     * @return mixed
     */
    public function getEstimateDisciplineByProject(Project $project, Request $request){
        $data = $this->getDataEstimateDiscipline($project,$request);
        $result = $data->mapToGroups(function ($location) use ($data) {
            $projectClass = new ProjectClass();
            $projectClass->estimateVolume = $location->volume;
            $projectClass->disciplineTitle = $location?->wbss?->wbsDiscipline?->title;
            $projectClass->workItemIdentifier = $location?->wbss?->identifier;
            $projectClass->workElementTitle = $location?->wbss?->work_element;
            $projectClass->workItemDescription = $location?->workItems?->description;
            $projectClass->workItemId = $location?->workItems?->id;
            $projectClass->workItemUnit = $location?->workItems?->unit;
            $projectClass->workItemUnitRateLaborCost = $this->getResultCount($location?->labor_unit_rate, $location?->labour_factorial);
            $projectClass->workItemTotalLaborCost = (float) $location?->labor_cost_total_rate;
            $projectClass->workItemUnitRateToolCost = $this->getResultCount($location?->tool_unit_rate, $location?->equipment_factorial);
            $projectClass->workItemTotalToolCost = (float) $location?->tool_unit_rate_total;
            $projectClass->workItemUnitRateMaterialCost = $this->getResultCount($location?->material_unit_rate,$location?->material_factorial);
            $projectClass->workItemTotalMaterialCost = (float) $location?->material_unit_rate_total;
            $projectClass->workItemLaborFactorial = $location?->labour_factorial;
            $projectClass->workItemEquipmentFactorial = $location?->equipment_factorial;
            $projectClass->workItemMaterialFactorial = $location?->material_factorial;
            $projectClass->workItemTotalCostStr = number_format($this->getTotalCostWorkItem($location),2);
            $projectClass->workItemTotalCost = $this->getTotalCostWorkItem($location);

            return [
                $location->wbss?->title => $projectClass,
            ];
        });

        return $result;
    }

    /**
     * Get All cost of Project
     * @param Project $project
     * @param Request $request
     * @return ProjectTotalCostClass
     */
    public function getAllProjectCost(Project $project, Request $request){
        $data = $this->getEstimateDisciplineByProject($project,$request);
        $projectTotalCost = $this->getProjectTotalCost($data);
        return $projectTotalCost;
    }

    public function getDataEstimateDiscipline(Project $project, Request $request){
        $data = EstimateAllDiscipline::with(['wbss.workElements','workItems.manPowers','workItems.equipmentTools','workItems.materials'])
            ->when($request->discipline == 'civil', function($q){
                return $q->where('work_scope','=','civil');
            })->when($request->discipline == 'mechanical', function($q){
                return $q->where('work_scope','=','mechanical');
            })->when($request->discipline == 'electrical', function($q){
                return $q->where('work_scope','=','electrical');
            })->when($request->discipline == 'instrument', function($q){
                return $q->where('work_scope','=','instrument');
            })->where('project_id',$project->id)->get();

        return $data;
    }

    /**
     * Sum total price category by location in project detail page estimate discipline
     * @return ProjectTotalCostClass
     */
    public function getProjectTotalCost($estimateDiscipline){
        $totalPriceLabor = 0;
        $totalPriceEquipment = 0;
        $totalPriceMaterial = 0;

        if($estimateDiscipline) {
            $costs = $estimateDiscipline->map(function ($item) use ($totalPriceLabor, $totalPriceEquipment, $totalPriceMaterial) {
                foreach ($item as $v) {
                    $totalPriceLabor += $v->workItemTotalLaborCost;
                    $totalPriceEquipment += $v->workItemTotalToolCost;
                    $totalPriceMaterial += $v->workItemTotalMaterialCost;
                }

                $costByDiscipline = $item->mapToGroups(function($discipline){
                    return [
                        $discipline?->disciplineTitle => $discipline
                    ];
                });

                $disciplineLaborCost = $costByDiscipline->map(function($cost){
                    return $cost->sum('workItemTotalLaborCost');
                });
                $disciplineToolCost = $costByDiscipline->map(function($cost){
                    return $cost->sum('workItemTotalToolCost');
                });
                $disciplineMaterialCost = $costByDiscipline->map(function($cost){
                    return $cost->sum('workItemTotalMaterialCost');
                });

                $costByElement = $item->mapToGroups(function($element){
                    return [
                        $element?->workElementTitle => $element
                    ];
                });

                $elementLaborCost = $costByElement->map(function($cost){
                    return $cost->sum('workItemTotalLaborCost');
                });

                $elementToolCost = $costByElement->map(function($cost){
                    return $cost->sum('workItemTotalToolCost');
                });

                $elementMaterialCost = $costByElement->map(function($cost){
                    return $cost->sum('workItemTotalMaterialCost');
                });

                $totalWorkCostByLocation = $totalPriceLabor + $totalPriceEquipment + $totalPriceMaterial;
                $projectTotalCost = new ProjectTotalCostClass();
                $projectTotalCost->totalLaborCost = $this->toCurrencyIDR($totalPriceLabor);
                $projectTotalCost->totalEquipmentCost = $this->toCurrencyIDR($totalPriceEquipment);
                $projectTotalCost->totalMaterialCost = $this->toCurrencyIDR($totalPriceMaterial);
                $projectTotalCost->totalWorkCost = $totalWorkCostByLocation;
                $projectTotalCost->disciplineLaborCost = $disciplineLaborCost;
                $projectTotalCost->disciplineToolCost = $disciplineToolCost;
                $projectTotalCost->disciplineMaterialCost = $disciplineMaterialCost;
                $projectTotalCost->elementLaborCost = $elementLaborCost;
                $projectTotalCost->elementToolCost = $elementToolCost;
                $projectTotalCost->elementMaterialCost = $elementMaterialCost;

                return $projectTotalCost;
            });
        }
        return $costs;
    }


    public function getTotalCostWorkItem($location){
        $labor_factorial = $location?->labour_factorial ?? 1;
        $tool_factorial = $location?->equipment_factorial ?? 1;
        $material_factorial = $location?->material_factorial ?? 1;
        $man_power_cost = (float) $location?->labor_unit_rate * $labor_factorial;
        $tool_cost = (float) $location?->tool_unit_rate * $tool_factorial;
        $material_cost = (float) $location?->material_unit_rate * $material_factorial;
        $totalWorkItemCost = $man_power_cost +  $tool_cost + $material_cost;
        $totalWorkItemCost = $totalWorkItemCost * $location->volume;

        return $totalWorkItemCost;
    }

    public function updateStatusProject(Project $project){
        if($project->getProjectStatusApproval() ==  Project::WAITING_FOR_APPROVAL){
            $project->status = Project::WAITING_FOR_APPROVAL;
        } else {
            $project->status = Project::PENDING_DISCIPLINE_APPROVAL;
        }
    }

    public function getDataEngineer($subject){
        return User::with('profiles')->whereHas('profiles', function ($q) use ($subject) {
            return $q->where('position', $subject);
        })->get();
    }

    public function checkReviewer($discipline, $designEngineer, $sizeEstimateDiscipline){
        $user = new User();
        $isReviewer = $user->isDisciplineReviewer($discipline);

        if(isset($designEngineer)
            && $sizeEstimateDiscipline > 0
            && $isReviewer){
            return true;
        }

        return false;
    }

    public function getRemarkDiscipline(Project $project){
        $data = json_decode($project->remark);
        return $data;
    }
    public function toCurrency($val){
        if(!$val) return 0.00;
        return number_format($val, 2);
    }

    public function toCurrencyIDR($val){
        if(!$val) return "0,00";
        return number_format($val, 2,',','.');
    }

    public function getResultCount($value,$factorial){
        if(!$value) return '';
        if(!$factorial) $factorial = 1;
        $newValue = $value * $factorial;
        return number_format($newValue,2,',','.');
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
