<?php

namespace App\Services;

use App\Class\ProjectClass;
use App\Class\ProjectTotalCostClass;
use App\Exports\SummaryExport;
use App\Mail\DisciplineApprovedMail;
use App\Mail\ReviewerDailyReminderMail;
use App\Mail\ReviewerReassignedMail;
use App\Mail\SendMail;
use App\Mail\SendNotifApproveCostEstimateToEngineer;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EstimateAllDiscipline;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ProjectApprovedNotification;
use App\Notifications\ProjectSubmittedForReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;
use Twilio\Rest\Client;

class ProjectServices
{
    public function getProjectsData(Request $request){
        $order = $request->order;
        $sort =  $request->sort;

        $requestFilter = request(['q','status','civil','mechanical','electrical','instrument','sponsor','it','architect','my_reviews']);

        $projects = Project::with(['designEngineerMechanical.profiles','designEngineerCivil.profiles','designEngineerElectrical.profiles','designEngineerInstrument.profiles','designEngineerIt.profiles','designEngineerArchitect.profiles','projectArea','projectManager.profiles'])
            ->when(!auth()->user()->isViewAllCostEstimateRole(), function ($subQuery){
                return $subQuery->access();
            });

        $countDraft = clone $projects;
        $countApprove = clone $projects;
        $countMyReviews = clone $projects;

        $projectList = $projects->filter($requestFilter, true)->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();
        $countDraft = $countDraft->filter($requestFilter,false)->where('status',Project::DRAFT)->count();
        $countApprove = $countApprove->filter($requestFilter,false)->where('status',Project::APPROVE)->count();

        $userId = auth()->id();
        $countMyReviews = $countMyReviews->where(function ($q) use ($userId) {
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
        })->count();

        return [
            'projectList' => $projectList,
            'draft' => $countDraft,
            'approve' => $countApprove,
            'myReviews' => $countMyReviews,
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
            $projectClass->id = $location->id;
            $projectClass->estimateVolume = $location->volume;
            $projectClass->disciplineTitle = $location?->wbss?->wbsDiscipline?->title;
            $projectClass->workItemIdentifier = $location?->wbss?->identifier;
            $projectClass->workElementTitle = $location?->wbss?->work_element;
            $projectClass->workItemDescription = $location?->workItems?->description ?? $location?->title;
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
            $projectClass->workScope = $location->work_scope;

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
        if(sizeof($project->getProjectDisciplineStatusApproval()) < 1){
            $project->status = Project::APPROVE;
        } else {
            $project->status = Project::PENDING_DISCIPLINE_APPROVAL;
        }
    }

    public function setRejectedDisciplineToWaiting(Project $project){
        if($project->mechanical_approval_status == Project::REJECTED){
            $project->mechanical_approval_status = Project::PENDING;
        }
        if($project->civil_approval_status == Project::REJECTED){
            $project->civil_approval_status = Project::PENDING;
        }
        if($project->instrument_approval_status == Project::REJECTED){
            $project->instrument_approval_status = Project::PENDING;
        }
        if($project->electrical_approval_status == Project::REJECTED){
            $project->electrical_approval_status = Project::PENDING;
        }
    }

    public function getDataEngineer($subject){
        return User::with('profiles')->whereHas('profiles', function ($q) use ($subject) {
            return $q->where('position', $subject);
        })->get();
    }

    public function checkReviewer($discipline, $approver, $designEngineer, $sizeEstimateDiscipline){
        // Only the specifically assigned reviewer for this discipline can click the approve icon.
        // The previous broad role-check (any role-holder with estimates) allowed engineers
        // who happened to share a reviewer role to click it — wrong behavior.
        return isset($approver) && $approver == auth()->user()->id;
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
        if(!$factorial) $factorial = 1;
        $factorial = (float) $factorial;
        if(!$value) return '';
        $newValue = $value * $factorial;
        return number_format($newValue,2,',','.');
    }

    public function setStatusDraft(Project $project){
        if($project->status == Project::APPROVE){
            $project->status = Project::WAITING_FOR_APPROVAL;
        }
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }

//    public function sendWa($project){
//        $sid    = getenv("TWILIO_AUTH_SID");
//        $token  = getenv("TWILIO_AUTH_TOKEN");
//        $wa_from= getenv("TWILIO_WHATSAPP_FROM");
//
//        $profile = $project->getProfileUser($project->instrument_approver);
//        $phone = $profile->phone_number;
//        $full_name = $profile->full_name;
//
//        $twilio = new Client($sid, $token);
//
//        $body = "There's one project in cost estimate web need to review by : ". $full_name . ". Project Name : ". $project->project_title;
//
//        $twilio->messages->create("whatsapp:$phone",["from" => "whatsapp:$wa_from", "body" => $body]);
//
//        Log::info('send wa');
//    }

    public function sendEmailToReviewer(Project $project, $discipline){
        $approver = $discipline.'_approver';
        $approverUserId = $project->$approver;
        $mail = $project->getProfileUser($approverUserId)?->email;
        if(isset($mail)) {
            try {
                Mail::to($mail)->send(new SendMail($project));
                Log::info('Email send to : '.$mail);
            } catch (Exception $e){
                Log::error($e->getMessage());
            }
        } else {
            Log::warning("No email found for {$discipline} reviewer in project ID: {$project->id}");
        }

        try {
            $approverUser = User::find($approverUserId);
            if ($approverUser) {
                $approverUser->notify(new ProjectSubmittedForReviewNotification(
                    $project->id,
                    $project->project_no ?? '',
                    $project->project_title,
                    $discipline
                ));
            }
        } catch (\Exception $e) {
            Log::error('DB notification failed (sendEmailToReviewer): ' . $e->getMessage());
        }
    }

    public function sendEmailReviewerReassigned(Project $project, int $oldReviewerUserId, string $disciplineLabel, string $newReviewerName): void
    {
        $mail = $project->getProfileUser($oldReviewerUserId)?->email;
        if ($mail) {
            try {
                Mail::to($mail)->send(new ReviewerReassignedMail($project, $disciplineLabel, $newReviewerName));
                Log::info("Reviewer reassignment email sent to old reviewer: {$mail}");
            } catch (\Exception $e) {
                Log::error("ReviewerReassignedMail failed for {$mail}: " . $e->getMessage());
            }
        }
    }

    public function sendEmailRemainderToReviewer() {
        $projects = Project::where('status', Project::PENDING_DISCIPLINE_APPROVAL)->get();

        $disciplineApproverMap = [
            'mechanical' => ['approver' => 'mechanical_approver', 'status_col' => 'mechanical_approval_status', 'label' => 'Mechanical'],
            'civil'      => ['approver' => 'civil_approver',      'status_col' => 'civil_approval_status',      'label' => 'Civil'],
            'electrical' => ['approver' => 'electrical_approver', 'status_col' => 'electrical_approval_status', 'label' => 'Electrical'],
            'instrument' => ['approver' => 'instrument_approver', 'status_col' => 'instrument_approval_status', 'label' => 'Instrument'],
            'it'         => ['approver' => 'it_approver',         'status_col' => 'it_approval_status',         'label' => 'IT'],
            'architect'  => ['approver' => 'architect_approver',  'status_col' => 'architect_approval_status',  'label' => 'Architecture'],
        ];

        // Group pending disciplines by reviewer user_id
        $reviewerMap = [];
        foreach ($projects as $project) {
            $datas = json_decode($project->estimate_discipline_status);
            foreach ($datas as $data) {
                $parts = explode('_', $data->position);
                $disciplineKey = $parts[2] ?? null;
                if (!$disciplineKey || !isset($disciplineApproverMap[$disciplineKey])) continue;

                $approverCol = $disciplineApproverMap[$disciplineKey]['approver'];
                $statusCol   = $disciplineApproverMap[$disciplineKey]['status_col'];
                $label       = $disciplineApproverMap[$disciplineKey]['label'];
                $approverId  = $project->$approverCol;

                if ($data->status === 'PUBLISH' && $project->$statusCol !== Project::APPROVE && $approverId) {
                    if (!isset($reviewerMap[$approverId])) {
                        $reviewerMap[$approverId] = ['profile' => null, 'items' => []];
                    }
                    // Find existing entry for this project or create one
                    $found = false;
                    foreach ($reviewerMap[$approverId]['items'] as &$entry) {
                        if ($entry['project']->id === $project->id) {
                            $entry['disciplines'][] = $label;
                            $found = true;
                            break;
                        }
                    }
                    unset($entry);
                    if (!$found) {
                        $reviewerMap[$approverId]['items'][] = [
                            'project'     => $project,
                            'disciplines' => [$label],
                        ];
                    }
                    if (!$reviewerMap[$approverId]['profile']) {
                        $reviewerMap[$approverId]['profile'] = Profile::where('user_id', $approverId)->first();
                    }
                }
            }
        }

        // Send one grouped email per reviewer
        foreach ($reviewerMap as $reviewerId => $data) {
            $profile = $data['profile'];
            if (!$profile || !$profile->email) continue;
            try {
                Mail::to($profile->email)->send(new ReviewerDailyReminderMail($profile->full_name, $data['items']));
                Log::info("Daily reminder sent to reviewer: {$profile->email} ({$profile->full_name}) — " . count($data['items']) . " project(s).");
            } catch (\Exception $e) {
                Log::error("Failed to send daily reminder to {$profile->email}: " . $e->getMessage());
            }
        }
    }

    public function sendDisciplineApprovedEmailToEngineer(Project $project, string $discipline): void
    {
        $disciplineToEngineerCol = [
            'civil'       => 'design_engineer_civil',
            'mechanical'  => 'design_engineer_mechanical',
            'electrical'  => 'design_engineer_electrical',
            'instrument'  => 'design_engineer_instrument',
            'it'          => 'design_engineer_it',
            'architect'   => 'design_engineer_architect',
        ];

        $key = strtolower($discipline);
        $engineerCol = $disciplineToEngineerCol[$key] ?? null;
        if (!$engineerCol || !$project->$engineerCol) return;

        $profile = Profile::where('user_id', $project->$engineerCol)->first();
        if (!$profile || !$profile->email) return;

        try {
            Mail::to($profile->email)->send(new DisciplineApprovedMail($project, $profile->full_name, ucfirst($key)));
            Log::info("Discipline approved ({$discipline}) email sent to: {$profile->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send discipline approved email for {$discipline}: " . $e->getMessage());
        }
    }

    public function sendEmailToEngineer(Project $project, Request $request)
    {
        Log::info("sendEmailToEngineer: triggered for project [{$project->id}] {$project->project_title}");

        $excelData = null;
        try {
            $blankRequest = new Request();
            $estimateDisciplines = $this->getEstimateDisciplineByProject($project, $blankRequest);
            $costProjects = $this->getProjectTotalCost($estimateDisciplines);
            $excelData = Excel::raw(
                new SummaryExport($estimateDisciplines, $project, $costProjects),
                \Maatwebsite\Excel\Excel::XLSX
            );
            Log::info("sendEmailToEngineer: Excel generated successfully for project [{$project->id}]");
        } catch (\Exception $e) {
            Log::error("sendEmailToEngineer: Excel generation failed for project [{$project->id}] — {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
        }

        foreach (Setting::DESIGN_ENGINEER_LIST_DB_COLUMN as $engineer) {
            if (!empty($project->$engineer)) {
                $profile = Profile::where('user_id', $project->$engineer)->first();
                if (!$profile || !$profile->email) continue;
                try {
                    Mail::to($profile->email)->send(new SendNotifApproveCostEstimateToEngineer($project, $profile, $excelData));
                    Log::info("sendEmailToEngineer: all-approved email sent to {$profile->email}" . ($excelData ? ' (with Excel)' : ' (no attachment — Excel failed)'));
                } catch (\Exception $e) {
                    Log::error("sendEmailToEngineer: mail failed for {$profile->email} — {$e->getMessage()}");
                }
            }
        }

        try {
            foreach (Setting::DESIGN_ENGINEER_LIST_DB_COLUMN as $engineer) {
                if (!empty($project->$engineer)) {
                    $engineerUser = User::find($project->$engineer);
                    $engineerUser?->notify(new ProjectApprovedNotification(
                        $project->id,
                        $project->project_no ?? '',
                        $project->project_title
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('DB notification failed (sendEmailToEngineer): ' . $e->getMessage());
        }
    }

    public function replicateEstimateDisciplineStatus($disciplineStatus){
        $disciplineStatus = json_decode($disciplineStatus);
        $newDisciplineStatus = [];
        foreach ($disciplineStatus as $status) {
            $newStatus = clone $status;
            $newStatus->status = Project::PENDING;
            $newDisciplineStatus[] = $newStatus;
        }
        return json_encode($newDisciplineStatus);
    }
}

