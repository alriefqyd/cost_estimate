<?php

namespace App\Http\Controllers;

use App\Exports\SummaryExport;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\WbsLevel3;
use App\Rules\DesignEngineerRule;
use App\Rules\UniqueProject;
use App\Services\ProjectServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, Project $project)
    {
        $projectService = new ProjectServices();
        $departmentController = new DepartmentsController();
        $civilEngineerList = $projectService->getDataEngineer('design_civil_engineer');
        $mechanicalEngineerList = $projectService->getDataEngineer('design_mechanical_engineer');
        $electricalEngineerList = $projectService->getDataEngineer('design_electrical_engineer');
        $instrumentEngineerList = $projectService->getDataEngineer('design_instrument_engineer');
        $itEngineerList = $projectService->getDataEngineer('design_it_engineer');
        $architectEngineerList = $projectService->getDataEngineer('design_architect_engineer');
        $this->authorize('viewAny',$project);
        $projectList = $projectService->getProjectsData($request)['projectList'];
        $departments = $departmentController->getAllSubDepartment();
        return view('project.index',[
            'projects' => $projectList,
            'projectDraft' => $projectService->getProjectsData($request)['draft'],
            'projectApprove' => $projectService->getProjectsData($request)['approve'],
            'civilEngineerList' => $civilEngineerList,
            'mechanicalEngineerList' => $mechanicalEngineerList,
            'electricalEngineerList' => $electricalEngineerList,
            'instrumentEngineerList' => $instrumentEngineerList,
            'itEngineerList' => $itEngineerList,
            'architectEngineerList' => $architectEngineerList,
            'departments' => $departments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $departmentController = new DepartmentsController();
        $departments = $departmentController->getAllSubDepartment();
        $this->authorize('create',Project::class);
        return view('project.create', [
            'departments' => $departments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $projectService = new ProjectServices();
        $this->authorize('create',Project::class);
        $request->validate([
            'project_title' => ['required', new UniqueProject(null)],
            'project_sponsor' => 'required',
            'project_manager' => 'required',
            'project_engineer' => 'required',
            'project_area' => 'required',
            'design_engineer_civil' => new DesignEngineerRule('design_engineer_civil'),
            'design_engineer_mechanical' => new DesignEngineerRule('design_engineer_mechanical'),
            'design_engineer_electrical' => new DesignEngineerRule('design_engineer_electrical'),
            'design_engineer_instrument' => new DesignEngineerRule('design_engineer_instrument'),
            'design_engineer_it' => new DesignEngineerRule('design_engineer_it'),
            'design_engineer_architect' => new DesignEngineerRule('design_engineer_architect')
        ]);

        try {
            DB::beginTransaction();
            $user = auth()->user();
            $project = new Project([
                'project_no' => $request->project_no,
                'project_title' => $request->project_title,
                'sub_project_title' => $request->sub_project_title,
                'project_sponsor' => $request->project_sponsor,
                'project_area_id' => $request->project_area,
                'project_manager' => $request->project_manager,
                'project_engineer' => $request->project_engineer,
                'design_engineer_mechanical' => $request->design_engineer_mechanical,
                'design_engineer_civil' => $request->design_engineer_civil,
                'design_engineer_electrical' => $request->design_engineer_electrical,
                'design_engineer_instrument' => $request->design_engineer_instrument,
                'design_engineer_it' => $request->design_engineer_it,
                'design_engineer_architect' => $request->design_engineer_architect,
                'mechanical_approval_status' => isset($request->design_engineer_mechanical) ? Project::PENDING : '',
                'civil_approval_status' => isset($request->design_engineer_civil) ? Project::PENDING : '',
                'electrical_approval_status' => isset($request->design_engineer_electrical) ? Project::PENDING : '',
                'instrument_approval_status' => isset($request->design_engineer_instrument) ? Project::PENDING : '',
                'mechanical_approver' => $request-> reviewer_mechanical,
                'civil_approver' => $request-> reviewer_civil,
                'electrical_approver' => $request-> reviewer_electrical,
                'instrument_approver' => $request->reviewer_instrument,
                'architect_approver' => $request->reviewer_architect,
                'it_approver' => $request->reviewer_it,
                'status' => Project::DRAFT,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            $statusDiscipline = [];
            foreach (Project::DESIGN_ENGINEER_KEY_LIST as $k => $v) {
                if(isset($request->$k)){
                    $data = [
                        'position' => $k,
                        'status' => "DRAFT"
                    ];
                    array_push($statusDiscipline, $data);
                }
            }

            if(isset($request->design_engineer_it) && !isset($request->design_engineer_instrument)) {
                $data = [
                    'position' => 'design_engineer_instrument',
                    'status' => "DRAFT"
                ];
                array_push($statusDiscipline, $data);
            }

            $project->estimate_discipline_status = json_encode($statusDiscipline);

            $project->save();
            DB::commit();
            $projectService->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('project/'.$project->id);
        } catch(\Exception $e){
            DB::rollBack();
            log::error($e->getMessage());
            return redirect('project/create')->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $user = User::with(['profiles','roles'])->get();
        $departmentController = new DepartmentsController();
        $departments = $departmentController->getAllSubDepartment();

        return view('project.edit',[
            'project' => $project,
            'users' => $user,
            'departments' => $departments
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Project $project)
    {
        $projectService = new ProjectServices();
        $this->authorize('update',$project);
        $request->validate([
            'project_title' => ['required', new UniqueProject($project->id)],
            'project_sponsor' => 'required',
            'project_manager' => 'required',
            'project_engineer' => 'required',
            'project_area' => 'required',
            'design_engineer_civil' => new DesignEngineerRule('design_engineer_civil'),
            'design_engineer_mechanical' => new DesignEngineerRule('design_engineer_mechanical'),
            'design_engineer_electrical' => new DesignEngineerRule('design_engineer_electrical'),
            'design_engineer_instrument' => new DesignEngineerRule('design_engineer_instrument'),
            'design_engineer_it' => new DesignEngineerRule('design_engineer_it'),
            'design_engineer_architect' => new DesignEngineerRule('design_engineer_architect'),
        ]);

        DB::beginTransaction();
        try {
           $project->project_no =  $request->project_no;
           $project->project_title = $request->project_title;
           $project->project_sponsor = $request->project_sponsor;
           $project->sub_project_title = $request->sub_project_title;
           $project->project_manager = $request->project_manager;
           $project->project_engineer = $request->project_engineer;
           $project->design_engineer_mechanical = $request->design_engineer_mechanical;
           $project->design_engineer_civil = $request->design_engineer_civil;
           $project->design_engineer_electrical = $request->design_engineer_electrical;
           $project->design_engineer_instrument = $request->design_engineer_instrument;
           $project->design_engineer_it = $request->design_engineer_it;
           $project->design_engineer_architect = $request->design_engineer_architect;
           if(isset($request->status)) $project->status = $request->status;
           $project->project_area_id = $request->project_area;
           $project->mechanical_approver = $request-> reviewer_mechanical;
           $project->civil_approver = $request-> reviewer_civil;
           $project->electrical_approver = $request-> reviewer_electrical;
           $project->instrument_approver = $request-> reviewer_instrument;
           $project->it_approver = $request->reviewer_it;
           $project->architect_approver = $request->reviewer_architect;
           $project->updated_by = auth()->user()->id;
           $projectService->setStatusDraft($project);

            $statusDiscipline = json_decode($project->estimate_discipline_status);
            $existingStatus = collect($statusDiscipline);

            if($statusDiscipline){
                foreach (Project::DESIGN_ENGINEER_KEY_LIST as $k => $v) {
                    $isAlreadyExistDiscipline = $existingStatus->contains('position',$k);
                    if(isset($request->$k) ){
                        if(!$isAlreadyExistDiscipline) {
                            $data = [
                                'position' => $k,
                                'status' => "DRAFT"
                            ];
                            array_push($statusDiscipline, $data);
                        }
                    } else {
                        $statusDiscipline = collect($statusDiscipline)->reject(function ($item) use ($k) {
                            if(!isset($item->position)) return null;
                            return $item->position === $k;
                        })->values()->toArray();
                    }
                }

            }

            $project->estimate_discipline_status = $statusDiscipline;
            $project->save();

           DB::commit();

            $projectService->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('project/'.$project->id);
        } catch (Exception $e) {
            DB::rollBack();
            log::error($e->getMessage());
            return redirect('project/edit/'.$project->id)->withErrors($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if(auth()->user()->cannot('delete',Project::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        DB::beginTransaction();
        try {
            $project->estimateAllDisciplines?->each(function($data){
                $data->delete();
            });
            $project->wbsLevel3s?->each(function ($data){
                $data->delete();
            });

            $project->delete();
            DB::commit();
            return response()->json([
               'status' => 200,
               'message' => 'Project Successfully deleted'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
               'status' => 500,
               'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function detail(Project $project, Request $request){
        $projectService = new ProjectServices();
        $estimateDisciplines = $projectService->getEstimateDisciplineByProject($project,$request);
        $costProjects = $projectService->getAllProjectCost($project, $request);
        $wbs = WbsLevel3::with(['wbsDiscipline'])->where('project_id',$project->id)->get()->groupBy('title');
        $this->authorize('view',$project);
        $project = $project->load(['projectArea','projectEngineer', 'projectManager']);
        $isReviewerCivil = $projectService->checkReviewer('civil',$project->civil_approver,$project->design_engineer_civil,sizeof($estimateDisciplines));
        $isReviewerMechanical = $projectService->checkReviewer('mechanical',$project->mechanical_approver,$project->design_engineer_mechanical,sizeof($estimateDisciplines));
        $isReviewerElectrical = $projectService->checkReviewer('electrical',$project->electrical_approver,$project->design_engineer_electrical,sizeof($estimateDisciplines));
        $isReviewerInstrument = $projectService->checkReviewer('instrument',$project->instrument_approver,$project->design_engineer_instrument,sizeof($estimateDisciplines));
        $isReviewerIt = $projectService->checkReviewer('instrument',$project->it_approver,$project->design_engineer_it,sizeof($estimateDisciplines));
        $isReviewerArchitect = $projectService->checkReviewer('architect',$project->architect_approver,$project->design_engineer_architect,sizeof($estimateDisciplines));
        $remark = $projectService->getRemarkDiscipline($project);

        return view('project.detail',[
            'project' => $project,
            'costProject' => $costProjects,
            'wbs' => $wbs,
            'remark' => $remark,
            'estimateAllDisciplines' => $estimateDisciplines,
            'isAuthorizeToReviewCivil' => $isReviewerCivil,
            'isAuthorizeToReviewMechanical' => $isReviewerMechanical,
            'isAuthorizeToReviewArchitect' => $isReviewerArchitect,
            'isAuthorizeToReviewElectrical' => $isReviewerElectrical,
            'isAuthorizeToReviewInstrument' => $isReviewerInstrument,
            'isAuthorizeToReviewIt' => $isReviewerIt,
            'project_date' => Carbon::parse($project->created_at)->format('d-M-Y'),
        ]);
    }

    public function duplicateProject(Project $project){
        DB::beginTransaction();
        try{
            $duplicateProject = $project->replicate();
            $duplicateProject->project_no = $project->project_no . '_copy';
            $duplicateProject->status = Project::DRAFT; // Set a valid status
            $duplicateProject->mechanical_approval_status = ""; // Set a valid status
            $duplicateProject->civil_approval_status = ""; // Set a valid status
            $duplicateProject->electrical_approval_status = ""; // Set a valid status
            $duplicateProject->instrument_approval_status = ""; // Set a valid status
            $duplicateProject->it_approval_status = ""; // Set a valid status
            $duplicateProject->architect_approval_status = ""; // Set a valid status
            $duplicateProject->remark = ""; // Set a valid status
            $duplicateProject->estimate_discipline_status = "";

            $duplicateProject->save();

            $project->load('wbsLevel3s', 'estimateAllDisciplines'); // eager loading
            $newId = $duplicateProject->id;
            $oldId = $project->id;
            $tempId = collect(); //store temp id to identify estimate discipline
            if(isset($project->wbsLevel3s)) {
                $project->wbsLevel3s()->chunk(100, function ($wbsLevel3s) use ($newId, $tempId, $oldId) {
                    foreach ($wbsLevel3s as $q) {
                        $newWbsLevel3 = $q->replicate();
                        $newWbsLevel3->project_id = $newId;
                        $newWbsLevel3->save();
                        $tempId->push([
                            'old' => $q->id,
                            'new' => $newWbsLevel3->id,
                            'project_id' => $oldId,
                        ]);
                    }
                });
            }


            if(isset($project->estimateAllDisciplines)){
                $project->estimateAllDisciplines->map(function ($q) use ($tempId, $newId, $oldId){
                   $wbsLevel3Id = $tempId->where('old', $q->wbs_level3_id)->first();
                   $newEstimate = $q->replicate();
                   $newEstimate->unique_identifier = uniqid();
                   $newEstimate->wbs_level3_id = $wbsLevel3Id['new'] ?? "";
                   $newEstimate->project_id = $newId;
                   $newEstimate->save();
                });
            }

            $data = [
                'project_id' => $duplicateProject->id
            ];

           DB::commit();
            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Success'
            ]);
        } catch (Exception $e){
            DB::rollBack();
            Log::alert($e->getMessage());
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkDuplicateProjectNo(Request $request){
        $data = Project::where('project_no',$request->projectNo)->first();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function export(Project $project, Request $request){
        $projectServices = new ProjectServices();
        $estimateDisciplines = $projectServices->getEstimateDisciplineByProject($project, $request);
        $costProjects = $projectServices->getAllProjectCost($project, $request);

        $settingController = new SettingController();
        $getUsdIdr =  $settingController->getUsdRateFromDB();
        Log::info('Export Estimate All Discipline Project ' . $project->project_title . ' by: ' . auth()->user()->profiles->full_name);
        // Pass data to a view
        $view = 'project.excel_format.summary';
        if($request->isDetail == "true"){
            $view = 'project.excel_format.detail';
        }

        $pdf = Pdf::loadView($view,[
            'project' => $project,
            'estimateAllDisciplines' => $estimateDisciplines,
            'costProject' => $costProjects,
            'isDetail' => true,
            'usdIdr' => $getUsdIdr
        ]) ->setPaper('A3', 'landscape');

        // Return the generated PDF
        return $pdf->download('summary-export.pdf');
    }

    public function getProjectDisciplineStatus(Request $request, Project $project){
        $result = '';
        $remark = json_decode($project->remark, true);
        switch ($request->discipline) {
            case Setting::DESIGN_ENGINEER_LIST['civil']:
                $result = $project->civil_approval_status;
                $remark = $remark['civil'] ?? '';
                break;
            case Setting::DESIGN_ENGINEER_LIST['mechanical']:
                $result = $project->mechanical_approval_status;
                $remark = $remark['mechanical'] ?? '';
                break;
            case Setting::DESIGN_ENGINEER_LIST['electrical']:
                $result = $project->electrical_approval_status;
                $remark = $remark['electrical'] ?? '';
                break;
            case Setting::DESIGN_ENGINEER_LIST['instrument']:
                $result = $project->instrument_approval_status;
                $remark = $remark['instrument'] ?? '';
                break;
            case Setting::DESIGN_ENGINEER_LIST['it']:
                $result = $project->it_approval_status;
                $remark = $remark['it'] ?? '';
                break;
            case Setting::DESIGN_ENGINEER_LIST['architect']:
                $result = $project->architect_approval_status;
                $remark = $remark['architect'] ?? '';
                break;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'result' => $result,
                'remark' => $remark
            ]
        ]);
    }

    public function updateStatus(Project $project, Request $request)
    {
        $projectServices = new ProjectServices();
        $remark = isset($project->remark) ? json_decode($project->remark, true) : [
            'civil' => '',
            'mechanical' => '',
            'electrical' => '',
            'instrument' => '',
            'it' => '',
            'architect' => ''
        ];

        try {
            DB::beginTransaction();

            if (isset($request->discipline)) {
                $discipline = strtolower($request->discipline);
                $column = $discipline.'_approval_status';
                $oldStatusDiscipline = $project->$column;

                switch ($request->discipline) {
                    case Setting::DESIGN_ENGINEER_LIST['civil']:
                        $project->civil_approval_status = $request->status;
                        $remark['civil'] = $request->remark;
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['mechanical']:
                        $project->mechanical_approval_status = $request->status;
                        $remark['mechanical'] = $request->remark;
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['electrical']:
                        $project->electrical_approval_status = $request->status;
                        $remark['electrical'] = $request->remark;
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['instrument']:
                        $project->instrument_approval_status = $request->status;
                        $remark['instrument'] = $request->remark;
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['architect']:
                        $project->architect_approval_status = $request->status;
                        $remark['architect'] = $request->remark;
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['it']:
                        $project->it_approval_status = $request->status;
                        $remark['it'] = $request->remark;
                        break;
                }

                $projectServices->updateStatusProject($project);

                $newStatusDiscipline = $request->status;
                $statusEstimate = collect(json_decode($project->estimate_discipline_status));
                if($oldStatusDiscipline == "APPROVE" || $newStatusDiscipline == "REJECTED" || $newStatusDiscipline == "PENDING"){
                    $statusEstimate = $statusEstimate->map(function ($item) use ($discipline){
                        $position = 'design_engineer_'.$discipline;
                        //find by user the position status tu update
                        if($item->position == $position){
                            $item->status = "DRAFT";
                        };

                        return $item;
                    });
                }

                $project->estimate_discipline_status = $statusEstimate;
                $project->remark = json_encode($remark);
            } else {
                $project->status = Project::APPROVE;
            }

            $project->save();
            DB::commit();

            $response = [
                'status' => 200,
                'message' => 'Project successfully approved',
                'data' => Project::APPROVE,
            ];

            return response()->json($response);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateRemark(Project $project, Request $request){
        DB::beginTransaction();
        try{
            $project->remark = $request->remark;
            $project->save();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Remark successfully added'
            ]);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendMailPreview(){
        $ps = new ProjectServices();
        $ps->sendEmailRemainderToReviewer();
//        return view('emails.approverNotification');
    }

}
