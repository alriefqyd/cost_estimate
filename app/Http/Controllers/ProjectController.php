<?php

namespace App\Http\Controllers;

use App\Exports\SummaryExport;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\WbsLevel3;
use App\Rules\UniqueProject;
use App\Services\ProjectServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;
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
            'project_title' => ['required', new UniqueProject],
            'project_sponsor' => 'required',
            'project_manager' => 'required',
            'project_engineer' => 'required',
            'project_area' => 'required',
        ]);

        try {
            DB::beginTransaction();
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
                'mechanical_approval_status' => isset($request->design_engineer_mechanical) ? Project::PENDING : '',
                'civil_approval_status' => isset($request->design_engineer_civil) ? Project::PENDING : '',
                'electrical_approval_status' => isset($request->design_engineer_electrical) ? Project::PENDING : '',
                'instrument_approval_status' => isset($request->design_engineer_instrument) ? Project::PENDING : '',
                'status' => Project::DRAFT
            ]);
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
        $data = $this->validate($request,[
            Rule::unique('projects')->ignore($project->project_no),
            'project_title' => 'required',
            'project_sponsor' => 'required',
            'project_manager' => 'required',
            'project_engineer' => 'required',
            'project_area' => 'required',
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
           $project->status = $request->status ?? Project::DRAFT;
           $project->project_area_id = $request->project_area;

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
        $isReviewerCivil = $projectService->checkReviewer(Setting::DESIGN_ENGINEER_LIST_KEY['civil'],$project->design_engineer_civil,sizeof($estimateDisciplines));
        $isReviewerMechanical = $projectService->checkReviewer(Setting::DESIGN_ENGINEER_LIST_KEY['mechanical'],$project->design_engineer_mechanical,sizeof($estimateDisciplines));
        $isReviewerElectrical = $projectService->checkReviewer(Setting::DESIGN_ENGINEER_LIST_KEY['electrical'],$project->design_engineer_electrical,sizeof($estimateDisciplines));
        $isReviewerInstrument = $projectService->checkReviewer(Setting::DESIGN_ENGINEER_LIST_KEY['instrument'],$project->design_engineer_instrument,sizeof($estimateDisciplines));
        $remark = $projectService->getRemarkDiscipline($project);

        return view('project.detail',[
            'project' => $project,
            'costProject' => $costProjects,
            'wbs' => $wbs,
            'remark' => $remark,
            'estimateAllDisciplines' => $estimateDisciplines,
            'isAuthorizeToReviewCivil' => $isReviewerCivil,
            'isAuthorizeToReviewMechanical' => $isReviewerMechanical,
            'isAuthorizeToReviewElectrical' => $isReviewerElectrical,
            'isAuthorizeToReviewInstrument' => $isReviewerInstrument,
            'project_date' => Carbon::parse($project->created_at)->format('d-M-Y'),
        ]);
    }

    public function duplicateProject(Project $project){
        DB::beginTransaction();
        try{
            $duplicateProject = $project->replicate();
            $duplicateProject->project_no = $project->project_no . '_copy';
            $duplicateProject->status = Project::DRAFT; // Set a valid status
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

           DB::commit();
            return response()->json([
                'status' => 200,
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
        $estimateDisciplines = $projectServices->getEstimateDisciplineByProject($project,$request);
        $costProjects = $projectServices->getAllProjectCost($project, $request);
        Log::info('Export Estimate All Discipline Project ' . $project->project_title . ' by: ' . auth()->user()->profiles->full_name);
        return Excel::download(new SummaryExport($estimateDisciplines,$project, $costProjects), 'summary-export.xlsx');
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
        ];

        try {
            DB::beginTransaction();

            if ($request->discipline) {
                switch ($request->discipline) {
                    case Setting::DESIGN_ENGINEER_LIST['civil']:
                        $project->civil_approval_status = $request->status;
                        $remark['civil'] = $request->remark;; // Replace 'YourValueForCivil' with the actual value
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['mechanical']:
                        $project->mechanical_approval_status = $request->status;
                        $remark['mechanical'] = $request->remark; // Replace 'YourValueForMechanical' with the actual value
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['electrical']:
                        $project->electrical_approval_status = $request->status;
                        $remark['electrical'] = $request->remark;; // Replace 'YourValueForElectrical' with the actual value
                        break;
                    case Setting::DESIGN_ENGINEER_LIST['instrument']:
                        $project->instrument_approval_status = $request->status;
                        $remark['instrument'] = $request->remark;; // Replace 'YourValueForInstrument' with the actual value
                        break;
                }
                $projectServices->updateStatusProject($project);
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

}
