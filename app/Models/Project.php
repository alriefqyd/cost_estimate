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
        'instrument_approval_status' => 'design_engineer_instrument'
    ];
    public const DESIGN_ENGINEER_KEY_LIST = [
        'design_engineer_civil' => 'Design Engineer Civil',
        'design_engineer_mechanical' => 'Design Engineer Mechanical',
        'design_engineer_electrical' => 'Design Engineer Electrical',
        'design_engineer_instrument' => 'Design Engineer Instrument'
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

    public function scopeReviewerAccess($query){
        return $query;
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
            $total = $this->getTotalCost() * (15/100);
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
            return self::WAITING_FOR_APPROVAL;
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

        $str = implode(", ", $arr);
        return $str;
    }

    public function getStatusApprovalDiscipline($status){
        if($status == $this::REJECTED){
            return '<span class="checkmark-icon" style="position: relative; top: -8px; right: 0; font-size: 0.9em; color: red;">&#10008; Rejected</span>';
        } else if ($status == $this::APPROVE){
            return '<span class="checkmark-icon" style="position: relative; top: -8px; right: 0; font-size: 0.9em; color: green;">&#10004; Approve</span>';
        } else {
            return '<span style="position: relative; top: -3px";><i class="m-l-5" data-feather="clock" style="width: 13px; color: #eebe0b"> </i> <span style="font-size: 11px; color: #f3c107; position: relative; top: -8px"> Waiting For Approval </span></span> ';
        }
    }

}
