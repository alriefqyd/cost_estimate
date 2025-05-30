<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];


    const APPROVE = 'APPROVE';
    const DRAFT = 'DRAFT';
    const REJECTED = 'REJECTED';
    const PENDING = 'PENDING';
    const PENDING_DISCIPLINE_APPROVAL = 'PENDING DISCIPLINE APPROVAL';
    const WAITING_FOR_APPROVAL = 'WAITING FOR APPROVAL PROJECT MANAGER / REVIEWER';
    const APPROVE_BY_DISCIPLINE_REVIEWER = 'APPROVE BY DISCIPLINE REVIEWER';

    const APPROVAL_DISCIPLINE_LIST = [
        'mechanical_approval_status' => 'design_engineer_mechanical',
        'civil_approval_status' => 'design_engineer_civil',
        'electrical_approval_status' => 'design_engineer_electrical',
        'instrument_approval_status' => 'design_engineer_instrument',
        'it_approval_status' => 'design_engineer_it',
        'architect_approval_status' => 'design_engineer_architect'
    ];
    public const DESIGN_ENGINEER_KEY_LIST = [
        'design_engineer_civil' => 'Design Engineer Civil',
        'design_engineer_mechanical' => 'Design Engineer Mechanical',
        'design_engineer_electrical' => 'Design Engineer Electrical',
        'design_engineer_instrument' => 'Design Engineer Instrument',
        'design_engineer_it' => 'Design Engineer IT',
        'design_engineer_architect' => 'Design Engineer Architect'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!in_array($model->status, self::getStatuses())) {
                throw new \InvalidArgumentException('Invalid status value.');
            }
        });
    }

    public static function getStatuses()
    {
        return [
            self::DRAFT,
            self::APPROVE,
        ];
    }

    public function projectArea(){
        return $this->belongsTo(Departments::class,'project_area_id');
    }

    public function estimateAllDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'project_id');
    }

    public function designEngineerMechanical(){
        return $this->belongsTo(User::class,'design_engineer_mechanical');
    }

    public function designEngineerCivil(){
        return $this->belongsTo(User::class,'design_engineer_civil');
    }

    public function designEngineerElectrical(){
        return $this->belongsTo(User::class,'design_engineer_electrical');
    }

    public function designEngineerInstrument(){
        return $this->belongsTo(User::class,'design_engineer_instrument');
    }

    public function designEngineerIt(){
        return $this->belongsTo(User::class,'design_engineer_it');
    }

    public function designEngineerArchitect(){
        return $this->belongsTo(User::class,'design_engineer_architect');
    }

    public function reviewerMechanical(){
        return $this->belongsTo(User::class,'mechanical_approver');
    }

    public function reviewerCivil(){
        return $this->belongsTo(User::class,'civil_approver');
    }

    public function reviewerElectrical(){
        return $this->belongsTo(User::class,'electrical_approver');
    }

    public function reviewerInstrument(){
        return $this->belongsTo(User::class,'instrument_approver');
    }

    public function reviewerIt(){
        return $this->belongsTo(User::class,'it_approver');
    }

    public function reviewerArchitect(){
        return $this->belongsTo(User::class,'architect_approver');
    }

    public function projectManager(){
        return $this->belongsTo(User::class,'project_manager');
    }

    public function projectEngineer(){
        return $this->belongsTo(User::class,'project_engineer');
    }

    public function workElements(){
        return $this->hasMany(WorkElement::class,'project_id');
    }

    public function wbsLevel3s(){
        return $this->hasMany(WbsLevel3::class,'project_id');
    }

    public function projectSettings(){
        return $this->hasOne(ProjectSettings::class, 'project_id');
    }

    public function scopeFilter($query, array $filters, $isCount){

        $query->when($filters['status'] ?? false, function ($query, $status) use ($isCount) {
            if($isCount) $query->where('status', $status);
        })->when($filters['q'] ?? false, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('project_title', 'like', '%' . $q . '%')
                    ->orWhere('project_no', 'like', '%' . $q . '%');
            });
        })->when($filters['sponsor'] ?? false, function($query, $q){
            $query->where('project_area_id',$q);
        })->when($filters['mechanical'] ?? false, function($query, $q){
            $query->where('design_engineer_mechanical',$q);
        })->when($filters['civil'] ?? false, function($query, $q){
            $query->where('design_engineer_civil',$q);
        })->when($filters['electrical'] ?? false, function($query, $q){
            $query->where('design_engineer_electrical',$q);
        })->when($filters['instrument'] ?? false, function($query, $q){
            $query->where('design_engineer_instrument',$q);
        })->when($filters['it'] ?? false, function($query, $q){
            $query->where('design_engineer_it',$q);
        })->when($filters['architect'] ?? false, function($query, $q){
            $query->where('design_engineer_architect',$q);
        })->when($filters['sponsor'] ?? false, function($query, $q){
            $query->where('project_area_id', $q);
        });
    }

    public function projectWbsLevel3(){
        return $this->hasMany(WbsLevel3::class,'project_id');
    }


    public function scopeAccess($query){
        $user = auth()->user();
        $isAssignee = $user->isAssigneeCostEstimateRole();
        $isViewAll = $user->isViewAllCostEstimateRole();
        return $query->when(!$isViewAll, function ($subQuery) use ($user,$isAssignee) {
            return $subQuery->when($isAssignee, function ($q) use ($user) {
                return $q->where('design_engineer_mechanical', $user->id)
                    ->orWhere('design_engineer_civil', $user->id)
                    ->orWhere('design_engineer_mechanical', $user->id)
                    ->orWhere('design_engineer_electrical', $user->id)
                    ->orWhere('design_engineer_instrument', $user->id)
                    ->orWhere('design_engineer_architect', $user->id)
                    ->orWhere('design_engineer_it', $user->id)
                    ->orwhere('project_manager', $user->id)
                    ->orWhere('project_engineer', $user->id)
                    ;
            })->when($user->isAllElectricalCostEstimateRole(), function ($q) use ($user) {
                return $q->orwhereNotNull('design_engineer_electrical');
            })->when($user->isAllInstrumentCostEstimateRole(), function ($q) use ($user) {
                return $q->orwhereNotNull('design_engineer_instrument');
            })->when($user->isAllMechanicalCostEstimateRole(), function ($q) use ($user) {
                return $q->orwhereNotNull('design_engineer_mechanical');
            })->when($user->isAllCivilCostEstimateRole(), function ($q) use ($user) {
                return $q->orwhereNotNull('design_engineer_civil');
            });
        });
    }

    public function isDesignEngineer(){
        $user = auth()->user()->id;
        if($this->design_engineer_electrical == $user ||
            $this->design_engineer_instrument == $user ||
            $this->design_engineer_mechanical == $user ||
            $this->design_engineer_civil == $user ||
            $this->design_engineer_it == $user ||
            $this->design_engineer_architect
        ){return true;}
        return false;
    }

    public function getTotalCost(){
        try{
            $labor = $this->estimateAllDisciplines->sum('labor_cost_total_rate');
            $tool = $this->estimateAllDisciplines->sum('tool_unit_rate_total');
            $material = $this->estimateAllDisciplines->sum('material_unit_rate_total');

            $total =  $labor + $tool + $material;
            return $total;
        } catch(Exception $e){
            return 0;
        }

    }

    public function getContingencyCost(){
        try{
            $total = $this->getTotalCost() * ($this->projectSettings->contingency/100);
            return $total;
        } catch(Exception $e){
            return 0;
        }
    }

    public function isReviewer(){
        return (in_array(auth()->user()->profiles?->position, PROFILE::REVIEWER));
    }

    public function getTotalCostWithContingency(){
        $total = $this->getTotalCost();
        $contingency = $this->getContingencyCost();
        return $total + $contingency;
    }

    public function getMechanicalEngineer(){
        if(!$this->designEngineerMechanical) return '';
        return $this->designEngineerMechanical?->profiles?->full_name;
    }

    public function getCivilEngineer(){
        if(!$this->designEngineerCivil) return '';
        return $this->designEngineerCivil?->profiles?->full_name;
    }

    public function getElectricalEngineer(){
        if(!$this->designEngineerElectrical) return '';
        return $this->designEngineerElectrical?->profiles?->full_name;
    }

    public function getInstrumentEngineer(){
        if(!$this->designEngineerInstrument) return '';
        return $this->designEngineerInstrument?->profiles?->full_name;
    }

    public function getItEngineer(){
        if(!$this->designEngineerIt) return '';
        return $this->designEngineerIt?->profiles?->full_name;
    }

    public function getArchitectEngineer(){
        if(!$this->designEngineerArchitect) return '';
        return $this->designEngineerArchitect?->profiles?->full_name;
    }

    public function getProjectDisciplineStatusApproval(){
        $list = [];
        foreach (self::APPROVAL_DISCIPLINE_LIST as $approval => $designEngineer){
            if(isset($this->$designEngineer)
                && $this->$approval != self::APPROVE){
                array_push($list, self::DESIGN_ENGINEER_KEY_LIST[$designEngineer]);
            }
        }
        return $list;
    }

    public function getProjectStatusApproval(){
        if(sizeof($this->getProjectDisciplineStatusApproval()) < 1) {
//            return self::WAITING_FOR_APPROVAL;
            return self::APPROVE;
        }
        return self::PENDING_DISCIPLINE_APPROVAL;
    }

    public function getAllEngineerExcel(){
        $arr = [];
        if($this->getMechanicalEngineer()){
            array_push($arr,$this->getMechanicalEngineer());
        }
        if($this->getCivilEngineer()){
            array_push($arr,$this->getCivilEngineer());
        }
        if($this->getElectricalEngineer()){
            array_push($arr,$this->getElectricalEngineer());
        }
        if($this->getInstrumentEngineer()){
            array_push($arr,$this->getInstrumentEngineer());
        }
        if($this->getItEngineer()){
            array_push($arr,$this->getItEngineer());
        }
        if($this->getArchitectEngineer()){
            array_push($arr,$this->getArchitectEngineer());
        }

        $str = implode(", ", $arr);
        return $str;
    }

    public function getStatusApprovalDiscipline($status, $reviewer){
        if($status == $this::REJECTED){
            return '<span class="checkmark-icon" style="position: relative; top: -8px; right: 0; font-size: 0.9em; color: red;">&#10008; Rejected </span>';
        } else if ($status == $this::APPROVE){
            return '<span class="checkmark-icon" style="position: relative; top: -8px; right: 0; font-size: 0.9em; color: green;">&#10004; Approve</span>';
        } else {
            return '<span style="position: relative; top: -3px";><i class="m-l-5" data-feather="clock" style="width: 13px; color: #eebe0b"> </i> <span style="font-size: 11px; color: #f3c107; position: relative; top: -8px"> Waiting For Approval </span></span> ';
        }
    }

    public function getProfileUser($user){
        $user = User::where('id', $user)->first();
        return $user->profiles ?? null;
    }

    public function getStatusEstimateDiscipline($discipline){
        if($discipline == null){
            $position = explode('_',auth()->user()->profiles?->position);
            $position = $position[1] ?? null;
            $discipline = 'design_engineer_' . $position;
        }

        $json = json_decode($this->estimate_discipline_status, true);
        $data = collect($json);
        $data = $data->filter(function($item) use ($discipline){
           return $item["position"] == $discipline ;
        })->pluck('status');

        if(isset($data[0]) && $data[0] == "PUBLISH") return true;
        return false;
    }

}
