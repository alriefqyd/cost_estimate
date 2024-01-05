<?php

namespace App\Providers;

use App\Http\Controllers\WorkItemTypeController;
use App\Models\EquipmentTools;
use App\Models\EquipmentToolsCategory;
use App\Models\EstimateAllDiscipline;
use App\Models\ManPower;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\WorkBreakdownStructure;
use App\Models\WbsLevel3;
use App\Models\WorkItemType;
use App\Policies\EquipmentToolsCategoryPolicy;
use App\Policies\EquipmentToolsPolicy;
use App\Policies\EstimateAllDisciplinePolicy;
use App\Policies\ManPowerPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\TeamPolicy;
use App\Policies\WbsLevel3Policy;
use App\Policies\WorkBreakdownStructurePolicy;
use App\Policies\WorkItemTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Project::class => ProjectPolicy::class,
        User::class => UserPolicy::class,
        WbsLevel3::class => WbsLevel3Policy::class,
        EstimateAllDiscipline::class => EstimateAllDisciplinePolicy::class,
        ManPower::class => ManPowerPolicy::class,
        EquipmentTools::class => EquipmentToolsPolicy::class,
        EquipmentToolsCategory::class => EquipmentToolsCategoryPolicy::class,
        WorkBreakdownStructure::class => WorkBreakdownStructurePolicy::class,
        WorkItemTypeController::class => WorkItemTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
