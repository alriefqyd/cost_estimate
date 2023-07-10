<?php

namespace App\Http\Controllers;

use App\Exports\SummaryExport;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\WbsLevel3;
use App\Services\ProjectServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
        $civilEngineerList = $projectService->getDataEngineer('design_civil_engineer');
        $mechanicalEngineerList = $projectService->getDataEngineer('design_mechanical_engineer');
        $electricalEngineerList = $projectService->getDataEngineer('design_electrical_engineer');
        $instrumentEngineerList = $projectService->getDataEngineer('design_instrument_engineer');
        $this->authorize('viewAny', Project::class);
        $projectList = $projectService->getProjectsData($request)['projectList'];
        return view('project.index',[
            'projects' => $projectList,
            'projectDraft' => $projectService->getProjectsData($request)['draft'],
            'projectPublish' => $projectService->getProjectsData($request)['publish'],
            'civilEngineerList' => $civilEngineerList,
            'mechanicalEngineerList' => $mechanicalEngineerList,
            'electricalEngineerList' => $electricalEngineerList,
            'instrumentEngineerList' => $instrumentEngineerList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create',Project::class);
        return view('project.create');
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
        $data = $this->validate($request,[
            'project_no' => 'required|unique:projects',
            'project_title' => 'required',
            'project_sponsor' => 'required',
            'project_manager' => 'required',
            'project_engineer' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $project = new Project([
                'project_no' => $request->project_no,
                'project_title' => $request->project_title,
                'sub_project_title' => $request->sub_project_title,
                'project_sponsor' => $request->project_sponsor,
                'project_manager' => $request->project_manager,
                'project_engineer' => $request->project_engineer,
                'design_engineer_mechanical' => $request->design_engineer_mechanical,
                'design_engineer_civil' => $request->design_engineer_civil,
                'design_engineer_electrical' => $request->design_engineer_electrical,
                'design_engineer_instrument' => $request->design_engineer_instrument,
                'status' => 'DRAFT'
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $user = User::with(['profiles','roles'])->get();

        return view('project.edit',[
            'project' => $project,
            'users' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            'project_engineer' => 'required'
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
           $project->status = $request->status ?? 'DRAFT';

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
    public function destroy($id)
    {

    }

    public function detail(Project $project, Request $request){
        $projectServices = new ProjectServices();
        $estimateDisciplines = $projectServices->getEstimateDisciplineByProject($project,$request);
        $costProjects = $projectServices->getAllProjectCost($project, $request);
        $wbs = WbsLevel3::with(['wbsDiscipline','workElements'])->where('project_id',$project->id)->get()->groupBy('title');
        //this function is not use temporary
        //$summary = $this->getSummaryCostEstimate($project, $request);

        $this->authorize('view',$project);

        return view('project.detail',[
            //'summary' => $summary,
            'project' => $project,
            'costProject' => $costProjects,
            'wbs' => $wbs,
            'estimateAllDisciplines' => $estimateDisciplines,
            'project_date' => Carbon::parse($project->created_at)->format('d-M-Y'),
        ]);
    }

    public function checkDuplicateProjectNo(Request $request){
        $data = Project::where('project_no',$request->projectNo)->first();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /** Deprecated */
    public function getSummaryCostEstimate(Project $project, Request $request){
        $data = $this->getEstimateDisciplineByProject($project,$request)->get()->groupBy('wbss.wbsDiscipline.title');
        $arrValueManPower = array();
        $arrValueEquipment = array();
        $summaryCollection = array();
        foreach($data as $key => $value){
            foreach($value as $k => $v){
                $totalManPower = $v->workItems->ManPowers->sum(function($item){
                   return (float) $item->pivot->amount;
                });

                $totalEquipment = $v->workItems->equipmentTools->sum(function($item) {
                    return (float) $item->pivot->amount;
                });

                $totalManPowerAmount = $v->volume * $totalManPower;
                $totalToolEquipmentAmount = $v->volume * $totalEquipment;
                array_push($arrValueManPower,$totalManPowerAmount);
                array_push($arrValueEquipment,$totalToolEquipmentAmount);
            }
        }

        $summaryTotalAmountManPower = array_sum($arrValueManPower);
        $summaryTotalEquipment = array_sum($arrValueEquipment);
        $summary = ([
            'manPowerAmount' => $summaryTotalAmountManPower,
            'toolEquipmentAmount' => $summaryTotalEquipment,
        ]);

        return $summary;
    }

    public function export(Project $project, Request $request){
        $projectServices = new ProjectServices();
        $estimateDisciplines = $projectServices->getEstimateDisciplineByProject($project,$request);
        $costProjects = $projectServices->getAllProjectCost($project, $request);
        return Excel::download(new SummaryExport($estimateDisciplines,$project, $costProjects), 'summary-export.xlsx');
    }

}
