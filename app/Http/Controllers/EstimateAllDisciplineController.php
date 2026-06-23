<?php

namespace App\Http\Controllers;

use App\Class\EstimateDisciplineClass;
use App\Class\ProjectClass;
use App\Events\EstimateRowChanged;
use App\Events\EstimateRowDeleted;
use App\Models\EstimateAllDiscipline;
use App\Models\Material;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Services\ProjectServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EstimateAllDisciplineController extends Controller
{
    private function getUserDiscipline(): string
    {
        $parts = explode('_', auth()->user()->profiles?->position ?? '');
        return $parts[1] ?? '';
    }

    private function isAdmin(): bool
    {
        return auth()->user()->profiles?->position === 'administrator';
    }

    private function buildBroadcastPayload(EstimateAllDiscipline $row): array
    {
        $projectServices = new ProjectServices();
        $totalCost = $projectServices->getTotalCostWorkItem($row);

        return [
            'uniqueIdentifier'       => $row->unique_identifier,
            'workScope'              => $row->work_scope,
            'workItemId'             => $row->work_item_id,
            'workItemDescription'    => $row->workItems?->description ?? $row->title,
            'volume'                 => $row->volume,
            'unit'                   => $row->workItems?->unit ?? '',
            'laborUnitRate'          => (float) $row->labor_unit_rate,
            'toolUnitRate'           => (float) $row->tool_unit_rate,
            'materialUnitRate'       => (float) $row->material_unit_rate,
            'labourFactorial'        => (float) ($row->labour_factorial ?? 1),
            'equipmentFactorial'     => (float) ($row->equipment_factorial ?? 1),
            'materialFactorial'      => (float) ($row->material_factorial ?? 1),
            'laborUnitRateTotalStr'  => $projectServices->getResultCount($row->labor_unit_rate, $row->labour_factorial),
            'toolUnitRateTotalStr'   => $projectServices->getResultCount($row->tool_unit_rate, $row->equipment_factorial),
            'materialUnitRateTotalStr' => $projectServices->getResultCount($row->material_unit_rate, $row->material_factorial),
            'totalCostStr'           => number_format($totalCost, 2, ',', '.'),
            'totalCost'              => $totalCost,
            'wbs_level3_id'          => $row->wbs_level3_id,
            'work_element_id'        => $row->equipment_location_id,
            'userName'               => auth()->user()->profiles?->full_name ?? auth()->user()->name,
            'userId'                 => auth()->user()->id,
        ];
    }

    public function getWorkItems(Request $request, Project $project)
    {
        $data = EstimateAllDiscipline::with(['workItems.manPowers', 'workItems.equipmentTools', 'workItems.materials'])
            ->where('project_id', $project->id)
            ->where('work_scope', $request->discipline)
            ->get();
        return $data;
    }

    public function getExistingWbsLevel3Id(Request $request)
    {
        $wbsLevel3 = WbsLevel3::where('identifier', $request->level1)
            ->where('discipline', $request->level2)
            ->where('work_element', $request->level3)
            ->first('id');

        return $wbsLevel3->id;
    }

    public function create(Project $project, Request $request)
    {
        if (!$project->isDesignEngineer() && !$this->isAdmin()) {
            abort(403);
        }

        $discipline = $this->getUserDiscipline();
        $statusEstimate = collect(json_decode($project->estimate_discipline_status));
        $statusEstimate = $statusEstimate->filter(function ($item) use ($discipline) {
            return $item->position == 'design_engineer_' . $discipline;
        })->pluck('status')->first();

        // Trim any whitespace that leaked into unique_identifier from the multi-line blade value attribute
        EstimateAllDiscipline::where('project_id', $project->id)
            ->whereNotNull('unique_identifier')
            ->each(function ($row) {
                $clean = trim($row->unique_identifier);
                if ($clean !== $row->unique_identifier) {
                    $row->unique_identifier = $clean ?: null;
                    $row->saveQuietly();
                }
            });

        // Backfill null or empty-string unique_identifiers
        EstimateAllDiscipline::where('project_id', $project->id)
            ->where(function ($q) {
                $q->whereNull('unique_identifier')->orWhere('unique_identifier', '');
            })
            ->each(function ($row) {
                $row->unique_identifier = (string) Str::uuid();
                $row->saveQuietly();
            });

        // Fix duplicate unique_identifiers — keep the first occurrence (lowest id), re-assign the rest
        $seen = [];
        EstimateAllDiscipline::where('project_id', $project->id)
            ->whereNotNull('unique_identifier')
            ->orderBy('id')
            ->each(function ($row) use (&$seen) {
                if (isset($seen[$row->unique_identifier])) {
                    $row->unique_identifier = (string) Str::uuid();
                    $row->saveQuietly();
                } else {
                    $seen[$row->unique_identifier] = true;
                }
            });

        $wbs = WbsLevel3::with(['workElements', 'estimateDisciplines.wbss.workElements', 'estimateDisciplines.workitems'])
            ->where('project_id', $project->id)->get();

        $wbs = $wbs->mapToGroups(fn($loc) => [$loc->title => $loc]);
        $wbs = $wbs->map(fn($discipline) => $discipline->mapToGroups(fn($disc) => [$disc->disciplines->title => $disc]));
        $wbs = $wbs->map(function ($workElement) {
            return $workElement->map(function ($we) {
                return $we->flatMap(function ($e) {
                    $map = collect([]);
                    $projectServices = new ProjectServices();
                    foreach ($e->estimateDisciplines as $ed) {
                        $projectClass = new ProjectClass();
                        $projectClass->estimateVolume = $ed->volume;
                        $projectClass->disciplineTitle = $ed->disciplines?->title;
                        $projectClass->workItemIdentifier = $ed?->wbss?->identifier;
                        $projectClass->workElementTitle = $ed?->wbss?->workElements?->title;
                        $projectClass->workItemDescription = $ed?->workItems?->description;
                        $projectClass->workItemId = $ed?->workItems?->id;
                        $projectClass->workItemUnit = $ed?->workItems?->unit;
                        $projectClass->workItemUnitRateTotalLaborCost = $projectServices->getResultCount($ed?->labor_unit_rate, $ed?->labour_factorial);
                        $projectClass->workItemUnitRateLaborCost = (float) $ed?->labor_unit_rate;
                        $projectClass->workItemTotalLaborCost = (float) $ed?->labor_cost_total_rate;
                        $projectClass->workItemUnitRateTotalToolCost = $projectServices->getResultCount($ed?->tool_unit_rate, $ed?->equipment_factorial);
                        $projectClass->workItemUnitRateToolCost = (float) $ed?->tool_unit_rate;
                        $projectClass->workItemTotalToolCost = (float) $ed?->tool_unit_rate_total;
                        $projectClass->workItemUnitRateTotalMaterialCost = $projectServices->getResultCount($ed?->material_unit_rate, $ed?->material_factorial);
                        $projectClass->workItemUnitRateMaterialCost = (float) $ed?->material_unit_rate;
                        $projectClass->workItemTotalMaterialCost = (float) $ed?->material_unit_rate_total;
                        $projectClass->workItemLaborFactorial = $ed?->labour_factorial;
                        $projectClass->workItemEquipmentFactorial = $ed?->equipment_factorial;
                        $projectClass->workItemMaterialFactorial = $ed?->material_factorial;
                        $projectClass->workItemTotalCostStr = number_format($projectServices->getTotalCostWorkItem($ed), 2, ',', '.');
                        $projectClass->workItemTotalCost = $projectServices->getTotalCostWorkItem($ed);
                        $projectClass->wbs_level3_id = $ed->wbs_level3_id;
                        $projectClass->work_element_id = $ed->wbss?->work_element;
                        $projectClass->unique_identifier = $ed->unique_identifier;
                        $projectClass->version = $ed->version;
                        $projectClass->workScope = $ed->work_scope;
                        $map->push($projectClass);
                    }
                    $returnData = $e;
                    if (sizeof($map) > 0) $returnData = $map;
                    return [$e->work_element => $returnData];
                });
            });
        });

        $version = EstimateAllDiscipline::where('project_id', $project->id)->first('version');
        $projectServices = new ProjectServices();

        // ── Flat rows for React ──────────────────────────────────────────────
        // Query directly from EstimateAllDiscipline to avoid key collisions in the
        // nested WBS grouping (two WbsLevel3 rows with the same work_element FK
        // under the same location+discipline would silently overwrite each other).
        $flatRows = EstimateAllDiscipline::with(['workItems', 'wbss.disciplines', 'wbss.workElements'])
            ->where('project_id', $project->id)
            ->get()
            ->map(function ($ed) use ($projectServices) {
                return [
                    'uid'                 => $ed->unique_identifier,
                    'location'            => $ed->wbss?->title ?? '',
                    'discipline'          => $ed->wbss?->disciplines?->title ?? '',
                    'workElement'         => (string) ($ed->wbss?->work_element ?? ''),
                    'wbs_level3_id'       => $ed->wbs_level3_id,
                    'work_element_id'     => $ed->equipment_location_id,
                    'workItemId'          => $ed->work_item_id,
                    'workItemDescription' => $ed->workItems?->description ?? $ed->title ?? '',
                    'volume'              => (float) ($ed->volume ?? 1),
                    'unit'                => $ed->workItems?->unit ?? '',
                    'laborRate'           => (float) ($ed->labor_unit_rate ?? 0),
                    'toolRate'            => (float) ($ed->tool_unit_rate ?? 0),
                    'materialRate'        => (float) ($ed->material_unit_rate ?? 0),
                    'labourFactorial'     => (float) ($ed->labour_factorial ?? 1),
                    'equipmentFactorial'  => (float) ($ed->equipment_factorial ?? 1),
                    'materialFactorial'   => (float) ($ed->material_factorial ?? 1),
                    'totalCost'           => (float) $projectServices->getTotalCostWorkItem($ed),
                    'rowTotal'            => (float) ($ed->labor_cost_total_rate ?? 0) + (float) ($ed->tool_unit_rate_total ?? 0) + (float) ($ed->material_unit_rate_total ?? 0),
                    'workScope'           => $ed->work_scope ?? '',
                    'scopeOwned'          => !is_null($ed->scope_owner_id),
                ];
            })
            ->values()
            ->toArray();

        // ── WBS options for "Add Row" dialog ─────────────────────────────────
        $wbsOptions = WbsLevel3::with(['disciplines'])
            ->where('project_id', $project->id)
            ->get()
            ->map(fn($w) => [
                'wbs_level3_id'   => $w->id,
                'work_element_id' => $w->work_element,
                'location'        => $w->title ?? '',
                'discipline'      => $w->disciplines?->title ?? '',
                'workElement'     => (string) ($w->work_element ?? ''),
                'label'           => ($w->title ?? '') . ' › ' . ($w->disciplines?->title ?? '') . ' › ' . ($w->work_element ?? ''),
            ])
            ->values();

        $allStatuses   = collect(json_decode($project->estimate_discipline_status ?? '[]'));
        $myStatusEntry = $allStatuses->firstWhere('position', 'design_engineer_' . $discipline);

        return view('estimate_all_discipline.create', [
            'project'               => $project,
            'estimateAllDiscipline' => $wbs,
            'version'               => $version?->version ?? 0,
            'flatRows'              => $flatRows,
            'wbsOptions'            => $wbsOptions,
            'publishStatus'         => $myStatusEntry?->status ?? 'DRAFT',
            'wsUrl'                 => env('COLLAB_WS_URL', 'ws://localhost:1234'),
            'userDiscipline'        => $discipline,
            'userName'              => auth()->user()->profiles?->full_name ?? auth()->user()->name,
            'isAdmin'               => $this->isAdmin(),
        ]);
    }

    public function autosave(Project $project, Request $request): JsonResponse
    {
        if (!$project->isDesignEngineer() && !$this->isAdmin()) {
            return response()->json(['status' => 403, 'message' => "Not authorized"]);
        }

        $position = $this->getUserDiscipline();
        $workItemController = new WorkItemController();

        DB::beginTransaction();
        try {
            $uniqueIdentifier = trim($request->unique_identifier ?? '');

            // Find by uid
            $row = $uniqueIdentifier
                ? EstimateAllDiscipline::where('project_id', $project->id)
                    ->where('unique_identifier', $uniqueIdentifier)
                    ->first()
                : null;

            // Ownership check: scope_owner_id set = owned row; only that discipline (or admin) may edit
            if ($row && $row->scope_owner_id && !$this->isAdmin() && $row->work_scope !== $position) {
                DB::rollBack();
                return response()->json(['status' => 403, 'message' => 'Not authorized to edit this item']);
            }

            $isNew = !$row;
            if ($isNew) {
                $row = new EstimateAllDiscipline();
                $row->project_id            = $project->id;
                $row->wbs_level3_id         = $request->wbs_level3;
                $row->equipment_location_id = $request->work_element;
                // Always fill work_scope; derive from WBS discipline when user has no discipline (e.g. admin)
                if ($position) {
                    $row->work_scope      = $position;
                    $row->scope_owner_id  = auth()->id();
                } else {
                    $wbs = \App\Models\WbsLevel3::with('disciplines')->find($request->wbs_level3);
                    $row->work_scope      = strtolower($wbs?->disciplines?->title ?? '');
                    $row->scope_owner_id  = null; // admin rows are unowned → editable by all
                }
            }
            // Always stamp the uid (assigns one to migrated old rows)
            $row->unique_identifier = $uniqueIdentifier;

            $row->title                   = $request->workItemText ?? '';
            $row->work_item_id            = $request->workItem;
            $row->volume                  = $request->vol > 0 ? $request->vol : 1;
            $row->labour_factorial        = $request->labourFactorial !== '' ? (float) $request->labourFactorial : null;
            $row->equipment_factorial     = $request->equipmentFactorial !== '' ? (float) $request->equipmentFactorial : null;
            $row->material_factorial      = $request->materialFactorial !== '' ? (float) $request->materialFactorial : null;
            $row->labor_unit_rate         = $workItemController->strToFloat($request->labourUnitRate);
            $row->labor_cost_total_rate   = $workItemController->strToFloat($request->totalRateManPowers) * $row->volume;
            $row->tool_unit_rate          = $workItemController->strToFloat($request->equipmentUnitRate);
            $row->tool_unit_rate_total    = $workItemController->strToFloat($request->totalRateEquipments) * $row->volume;
            $row->material_unit_rate      = $workItemController->strToFloat($request->materialUnitRate);
            $row->material_unit_rate_total = $workItemController->strToFloat($request->totalRateMaterials) * $row->volume;
            $row->save();

            $payload = $this->buildBroadcastPayload($row->load('workItems'));
            try {
                broadcast(new EstimateRowChanged($project->id, $position, $row->unique_identifier, $payload));
            } catch (Exception $broadcastException) {
                report($broadcastException);
            }

            DB::commit();
            return response()->json([
                'status'  => 200,
                'message' => 'Saved',
                'uid'     => $row->unique_identifier,
                'payload' => $payload,   // used by Yjs client to broadcast to other users
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function destroyRow(Project $project, string $uniqueIdentifier): JsonResponse
    {
        if (!$project->isDesignEngineer() && !$this->isAdmin()) {
            return response()->json(['status' => 403, 'message' => "Not authorized"]);
        }

        $position         = $this->getUserDiscipline();
        $uniqueIdentifier = trim($uniqueIdentifier);

        DB::beginTransaction();
        try {
            $row = EstimateAllDiscipline::where('project_id', $project->id)
                ->where('unique_identifier', $uniqueIdentifier)
                ->first();

            // Owned rows (scope_owner_id set) can only be deleted by that discipline or admin
            if ($row && $row->scope_owner_id && !$this->isAdmin() && $row->work_scope !== $position) {
                DB::rollBack();
                return response()->json(['status' => 403, 'message' => 'Not authorized to delete this item']);
            }

            EstimateAllDiscipline::where('project_id', $project->id)
                ->where('unique_identifier', $uniqueIdentifier)
                ->delete();

            try {
                broadcast(new EstimateRowDeleted($project->id, $uniqueIdentifier));
            } catch (Exception $broadcastException) {
                report($broadcastException);
            }

            DB::commit();
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function publish(Project $project, Request $request): JsonResponse
    {
        if (!$project->isDesignEngineer()) {
            return response()->json(['status' => 403, 'message' => "Not authorized"]);
        }

        $position = $this->getUserDiscipline();
        $projectServices = new ProjectServices();

        DB::beginTransaction();
        try {
            // Save contingency if provided
            if ($request->has('contingency')) {
                $project->projectSettings()->updateOrCreate(
                    ['project_id' => $project->id],
                    ['contingency' => $request->contingency]
                );
            }

            $positionDesign  = 'design_engineer_' . $position;
            $statusEstimate  = collect(json_decode($project->estimate_discipline_status));
            $statusEstimate  = $statusEstimate->map(function ($item) use ($positionDesign) {
                if ($item->position == $positionDesign) {
                    $item->status = 'PUBLISH';
                }
                return $item;
            });

            $project->estimate_discipline_status = $statusEstimate;
            $project->status = Project::PENDING_DISCIPLINE_APPROVAL;
            $projectServices->sendEmailToReviewer($project, $position);
            $projectServices->setRejectedDisciplineToWaiting($project);
            $project->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Published successfully']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveContingency(Project $project, Request $request): JsonResponse
    {
        try {
            $project->projectSettings()->updateOrCreate(
                ['project_id' => $project->id],
                ['contingency' => $request->contingency]
            );
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    // Legacy bulk-save — kept for the sync flow, now scoped to discipline
    public function update(Project $project, Request $request)
    {
        $workItemController = new WorkItemController();
        $projectServices    = new ProjectServices();
        $position           = $this->getUserDiscipline();

        DB::beginTransaction();
        try {
            $workItems = json_decode($request->work_items, true);
            if (sizeof($workItems) > 0) {
                $existingEstimateDiscipline = $this->getExistingWorkItemByWbs($request, $position);
                if ($existingEstimateDiscipline) {
                    if (!auth()->user()->canAny(['update', 'create'], EstimateAllDiscipline::class)) {
                        return response()->json(['status' => 403, 'message' => "You're not authorized"]);
                    }
                    foreach ($existingEstimateDiscipline as $item) {
                        $item->delete();
                    }
                }
            }

            $record     = EstimateAllDiscipline::where('project_id', $request->project_id)->where('work_scope', $position)->first();
            $newVersion = ($record?->version ?? 0) + 1;

            foreach ($workItems as $item) {
                $row = new EstimateAllDiscipline();
                $row->title                  = $item['workItemText'];
                $row->work_item_id           = $item['workItem'];
                $row->volume                 = $item['vol'] > 0 ? $item['vol'] : 1;
                $row->project_id             = $request->project_id ?? $item['project_id'];
                $row->work_scope             = $position;
                $row->labour_factorial       = isset($item['labourFactorial']) && $item['labourFactorial'] !== '' ? (float) $item['labourFactorial'] : null;
                $row->equipment_factorial    = isset($item['equipmentFactorial']) && $item['equipmentFactorial'] !== '' ? (float) $item['equipmentFactorial'] : null;
                $row->material_factorial     = isset($item['materialFactorial']) && $item['materialFactorial'] !== '' ? (float) $item['materialFactorial'] : null;
                $row->labor_unit_rate        = $workItemController->strToFloat($item['labourUnitRate']);
                $row->labor_cost_total_rate  = $workItemController->strToFloat($item['totalRateManPowers']) * $item['vol'];
                $row->tool_unit_rate         = $workItemController->strToFloat($item['equipmentUnitRate']);
                $row->tool_unit_rate_total   = $workItemController->strToFloat($item['totalRateEquipments']) * $item['vol'];
                $row->material_unit_rate     = $workItemController->strToFloat($item['materialUnitRate']);
                $row->material_unit_rate_total = $workItemController->strToFloat($item['totalRateMaterials']) * $item['vol'];
                $row->wbs_level3_id          = $item['wbs_level3'];
                $row->equipment_location_id  = $item['work_element'];
                $row->unique_identifier      = $item['idx'];
                $row->version                = $newVersion;
                $row->save();
            }

            $project->projectSettings()->updateOrCreate(
                ['project_id' => $project->id],
                ['contingency' => $request->contingency]
            );

            if ($request->estimateStatus == 'PUBLISH') {
                $positionDesign = 'design_engineer_' . $position;
                $statusEstimate = collect(json_decode($project->estimate_discipline_status));
                $statusEstimate = $statusEstimate->map(function ($item) use ($positionDesign) {
                    if ($item->position == $positionDesign) $item->status = 'PUBLISH';
                    return $item;
                });
                $project->estimate_discipline_status = $statusEstimate;
                $project->status = Project::PENDING_DISCIPLINE_APPROVAL;
                $projectServices->sendEmailToReviewer($project, $position);
                $projectServices->setRejectedDisciplineToWaiting($project);
            }
            $project->save();

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Success', 'version' => $newVersion]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function getItemAdditional(Request $request)
    {
        $arrayResult        = [];
        $manPowerController = new ManPowerController();
        $materialController = new MaterialController();

        switch ($request->type) {
            case 'manPower':
                $data = $manPowerController->getAllManPower($request);
                foreach ($data as $item) {
                    $arrayResult[] = [
                        'text' => $item?->title,
                        'id'   => $item?->id,
                        'rate' => $item?->overall_rate_hourly,
                    ];
                }
                break;
            case 'material':
                $data = $materialController->getAllMaterial($request);
                break;
        }

        return $arrayResult;
    }

    private function getExistingWorkItemByWbs(Request $request, string $workScope)
    {
        return EstimateAllDiscipline::with([
            'wbss.workElements.wbsDiscipline',
            'workItems.materials',
            'workItems.manPowers',
            'workItems.equipmentTools',
        ])
            ->where('project_id', $request->project_id)
            ->where('work_scope', $workScope)
            ->get();
    }

    public function getEstimateToSync(Request $request)
    {
        try {
            $data = EstimateAllDiscipline::with(['workItems.materials'])
                ->where('project_id', $request->project_id)
                ->orderBy('id', 'DESC')
                ->get();

            $projectServices   = new ProjectServices();
            $estimateConflict  = [];

            foreach ($request->estimate_sync as $cv) {
                $version          = $cv['version'] ?? null;
                $estimateToSync   = new EstimateDisciplineClass();
                $estimateToSync->workItemId             = $cv['workItem'];
                $estimateToSync->workItemDescription    = $cv['workItemText'];
                $estimateToSync->workItemVolume         = $cv['vol'] > 0 ? $cv['vol'] : 1;
                $estimateToSync->workItemManPowerCost   = $cv['totalRateManPowers'];
                $estimateToSync->workItemEquipmentCost  = $cv['totalRateEquipments'];
                $estimateToSync->workItemMaterialCost   = $cv['totalRateMaterials'];
                $estimateToSync->workItemManPowerCostRate   = $cv['labourUnitRate'];
                $estimateToSync->workItemEquipmentCostRate  = $cv['equipmentUnitRate'] ?? null;
                $estimateToSync->workItemMaterialCostRate   = $cv['materialUnitRate'] ?? null;
                $estimateToSync->laborFactorial    = (float) $cv['labourFactorial'] > 0 ? (float) $cv['labourFactorial'] : 1;
                $estimateToSync->equipmentFactorial = (float) $cv['equipmentFactorial'] > 0 ? (float) $cv['equipmentFactorial'] : 1;
                $estimateToSync->materialFactorial  = (float) $cv['materialFactorial'] > 0 ? (float) $cv['materialFactorial'] : 1;
                $estimateToSync->wbsLevel3Id        = $cv['wbs_level3'];
                $estimateToSync->uniqueIdentifier   = $cv['idx'];
                $estimateToSync->version            = $version;
                $estimateToSync->total              = number_format($this->countTotalCostWorkItem($estimateToSync), 2, ',', '.');
                array_push($estimateConflict, $estimateToSync);
            }

            $uniqueIdentifierArr = [];
            $estimateAlreadySave = $data->map(function ($item) use (&$uniqueIdentifierArr, $projectServices) {
                $material        = $item->workItems?->materials;
                $totalMaterial   = $material->reduce(fn($acc, $v) => $acc + $v->rate * $v->pivot?->quantity, 0);
                $equipmentTools  = $item->workItems?->equipmentTools;
                $totalEquipment  = $equipmentTools->reduce(fn($acc, $v) => $acc + $v->local_rate * $v->pivot?->quantity, 0);
                $manPowers       = $item->workItems?->manPowers;
                $totalManPowers  = $manPowers->reduce(function ($acc, $v) {
                    $coef = ($v->pivot?->labor_coefisient !== null && $v->pivot?->labor_coefisient !== '')
                        ? (float) $v->pivot?->labor_coefisient : 1;
                    return $acc + $v->overall_rate_hourly * $coef;
                }, 0);

                $est = new EstimateDisciplineClass();
                $est->workItemId             = $item->work_item_id;
                $est->workItemDescription    = $item->workItems?->description;
                $est->workItemVolume         = $item->volume;
                $est->workItemManPowerCost   = $totalManPowers;
                $est->workItemEquipmentCost  = $totalEquipment;
                $est->workItemMaterialCost   = $totalMaterial;
                $est->workItemManPowerCostRate   = $totalManPowers;
                $est->workItemEquipmentCostRate  = $totalEquipment;
                $est->workItemMaterialCostRate   = $totalMaterial;
                $est->laborFactorial    = (float) $item->labour_factorial > 0 ? (float) $item->labour_factorial : 1;
                $est->equipmentFactorial = (float) $item->equipment_factorial > 0 ? (float) $item->equipment_factorial : 1;
                $est->materialFactorial  = (float) $item->material_factorial > 0 ? (float) $item->material_factorial : 1;
                $est->wbsLevel3Id        = $item->wbs_level3_id;
                $est->version            = $item->version;
                $est->uniqueIdentifier   = $item->unique_identifier;
                $est->total              = number_format($this->countTotalCostWorkItem($est), 2, ',', '.');
                $est->unit               = $item->workItems?->unit;
                $uniqueIdentifierArr[]   = $item->unique_identifier;
                return $est;
            });

            $arrConflict = [];
            foreach ($estimateConflict as $ec) {
                if (!in_array($ec->uniqueIdentifier, $uniqueIdentifierArr) && !isset($ec->version)) {
                    $arrConflict[] = $ec;
                }
            }

            $version = $estimateAlreadySave[0]->version ?? 0;

            return response()->json([
                'status'  => 200,
                'data'    => [
                    'existingEstimate' => $estimateAlreadySave,
                    'itemToMerge'      => $arrConflict,
                    'version'          => $version,
                    'current_version'  => $request->current_version,
                ],
                'message' => 'Success Synchronize Data',
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function countTotalCostWorkItem($location)
    {
        $man_power_cost  = (float) $location?->workItemManPowerCost * ($location?->laborFactorial ?? 1);
        $tool_cost       = (float) $location?->workItemEquipmentCost * ($location?->equipmentFactorial ?? 1);
        $material_cost   = (float) $location?->workItemMaterialCost * ($location?->materialFactorial ?? 1);
        return ($man_power_cost + $tool_cost + $material_cost) * (float) $location->workItemVolume;
    }

    public function deleteEstimateDisciplineMoreOneMonth()
    {
        try {
            DB::beginTransaction();
            $date = Carbon::now()->subMonth();
            EstimateAllDiscipline::whereNotNull('deleted_at')->where('deleted_at', '<', $date)->forceDelete();
            DB::commit();
            Log::info('Hard-deleted estimate discipline records older than 1 month');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
