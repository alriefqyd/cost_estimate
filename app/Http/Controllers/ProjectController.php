<?php

namespace App\Http\Controllers;

use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Models\WorkBreakdownStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $projects = Project::with(['designEngineerMechanical.profiles','designEngineerCivil.profiles','designEngineerElectrical.profiles','designEngineerInstrument.profiles'])
            ->filter(request(['q']))->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();
        return view('project.index',[
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     *
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            ]);
            $project->save();
            DB::commit();
            return redirect('project/'.$project->id);
        } catch(\Exception $e){
            DB::rollBack();
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $workItemController = new workItemController();
        $estimateDisciplines = $this->getEstimateDisciplineByProject($project,$request)->get()->groupBy('wbss.title');
        $wbs = WbsLevel3::with(['wbsDiscipline','workElements'])->where('project_id',$project->id)->get()->groupBy('title');
        $summary = $this->getSummaryCostEstimate($project, $request);
        return view('project.detail',[
            'project' => $project,
            'wbs' => $wbs,
            'summary' => $summary,
            'estimateAllDisciplines' => $estimateDisciplines,
            'project_date' => Carbon::parse($project->created_at)->format('d-M-Y'),
        ]);
    }

    public function getEstimateDisciplineByProject(Project $project, Request $request){
        $data = EstimateAllDiscipline::with(['wbss','wbsLevels3.workElements','workItems.manPowers','workItems.equipmentTools','workItems.materials'])
            ->when($request->discipline == 'civil', function($q){
                return $q->where('work_scope','=','civil');
            })->when($request->discipline == 'mechanical', function($q){
                return $q->where('work_scope','=','mechanical');
            })->when($request->discipline == 'electrical', function($q){
                return $q->where('work_scope','=','electrical');
            })->when($request->discipline == 'instrument', function($q){
                return $q->where('work_scope','=','instrument');
            })->where('project_id',$project->id);

        return $data;
    }
    public function checkDuplicateProjectNo(Request $request){
        $data = Project::where('project_no',$request->projectNo)->first();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function getSummaryCostEstimate(Project $project, Request $request){
        $data = $this->getEstimateDisciplineByProject($project,$request)->get()->groupBy('wbsLevels3.wbsDiscipline.title');
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
}
