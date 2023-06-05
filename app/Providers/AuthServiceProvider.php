<?php

namespace App\Providers;

use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\WorkBreakdownStructure;
use App\Policies\EstimateAllDisciplinePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use App\Policies\TeamPolicy;
use App\Policies\WorkBreakdownStructurePolicy;
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
        WorkBreakdownStructure::class => WorkBreakdownStructurePolicy::class,
        EstimateAllDiscipline::class => EstimateAllDisciplinePolicy::class
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
