<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role',
        'profile_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function profiles(){
        return $this->hasOne(Profile::class);
    }
    public function projectEngineerMechanical(){
        return $this->hasOne(Project::class,'design_engineer_mechanical');
    }

    public function projectEngineerCivil(){
        return $this->hasOne(Project::class,'design_engineer_civil');
    }

    public function projectEngineerElectrical(){
        return $this->hasOne(Project::class,'design_engineer_electrical');
    }

    public function projectEngineerInstrument(){
        return $this->hasOne(Project::class,'design_engineer_instrument');
    }

    public function projectEngineer(){
        return $this->hasOne(Project::class,'project_engineer');
    }

    public function projectManager(){
        return $this->hasOne(Project::class,'project_manager');
    }

    public function roles(){
        return $this->belongsToMany(Role::class,'user_role')->withPivot('created_by','updated_by');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('name','like','%'.$q.'%')
            ->orWhere('email','like','%'.$q.'%')
        );
    }

    public function getDecryptPass(){
        return Crypt::decryptString($this->password);
    }

    /*
     * Below is checking for cost estimate role
     */
    public function isCostEstimateReviewer(){
        return auth()->user()->roles->contains('name',Role::ACTION_COST_ESTIMATE['review_cost_estimate']);
    }

    public function isDisciplineReviewer($discipline) {
        $reviewerDiscipline = '';
        if ($discipline) $reviewerDiscipline = Role::ACTION_COST_ESTIMATE["review_{$discipline}_cost_estimate"];
        $reviewerRoles = [
            $reviewerDiscipline,
            Role::ACTION_COST_ESTIMATE['review_cost_estimate'],
            Role::ACTION_COST_ESTIMATE['review_all_discipline_cost_estimate']
        ];

        return auth()->user()->roles->whereIn('name', $reviewerRoles)->isNotEmpty();
    }

    public function isViewAllCostEstimateRole(){
        return auth()->user()->roles->contains('name', Role::ACTION_COST_ESTIMATE['read_all']);
    }
    public function isAssigneeCostEstimateRole(){
        return auth()->user()->roles->contains('name',Role::ACTION_COST_ESTIMATE['read_assignee']);
    }

    public function isAllInstrumentCostEstimateRole(){
        return auth()->user()->roles->contains('name', Role::ACTION_COST_ESTIMATE['read_instrument']);
    }

    public function isAllElectricalCostEstimateRole(){
        return auth()->user()->roles->contains('name', Role::ACTION_COST_ESTIMATE['read_electrical']);
    }
    public function isAllMechanicalCostEstimateRole(){
        return auth()->user()->roles->contains('name', Role::ACTION_COST_ESTIMATE['read_mechanical']);
    }
    public function isAllCivilCostEstimateRole(){
        return auth()->user()->roles->contains('name', Role::ACTION_COST_ESTIMATE['read_civil']);
    }

    /*
     * Function for checking role feature
     */
    public function checkReviewerAuthorization($action, $feature){
        return auth()->user()->roles->contains(function ($role) use ($action, $feature) {
            return $role->action === $action
                && $role->feature === $feature;
        });
    }

    /*
     * Below is checking for work item role
     */
    public function isWorkItemReviewer(){
        return $this->checkReviewerAuthorization('reviewer','work_item');
    }

    /*
     * Checking for Man Power Role
     */
    public function isManPowerReviewer(){
        return $this->checkReviewerAuthorization('reviewer','man_power');
    }

    /*
     * Checking for Tools and Equipment Role
     */
    public function isToolsEquipmentReviewerRole(){
        return $this->checkReviewerAuthorization('reviewer','tool_equipment');
    }

    /*
     * Checking for Material Role
     */
    public function isMaterialReviewerRole(){
        return $this->checkReviewerAuthorization('reviewer','material');
    }

}
