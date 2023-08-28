<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->isViewAllCostEstimateRole()) {
            return true;
        }

        $roleMapping = [
            Role::ACTION_COST_ESTIMATE['read_all'],
            Role::ACTION_COST_ESTIMATE['read_electrical'],
            Role::ACTION_COST_ESTIMATE['read_instrument'],
            Role::ACTION_COST_ESTIMATE['read_assignee'],
            Role::ACTION_COST_ESTIMATE['read_civil'],
            Role::ACTION_COST_ESTIMATE['read_mechanical'],
        ];

        return auth()->user()->roles->whereIn('name', $roleMapping)->isNotEmpty();

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project){
        if ($user->isViewAllCostEstimateRole()) {
            return true;
        }

        $roleMapping = [
            'mechanical' => 'design_engineer_mechanical',
            'civil' => 'design_engineer_civil',
            'electrical' => 'design_engineer_electrical',
            'instrument' => 'design_engineer_instrument'
        ];

        foreach ($roleMapping as $role => $field) {
            if ($user->isAssigneeCostEstimateRole() && $project->$field == $user->id) {
                return true;
            }

            if ($user->{"isAll{$role}CostEstimateRole"}() && isset($project->$field) && $project->$field !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === 'create');
        });

        return $hasCostEstimateUpdatePermission;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Project $project)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === 'update');
        });

        return $hasCostEstimateUpdatePermission;

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === 'delete');
        });

        return $hasCostEstimateUpdatePermission;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Project $project)
    {
        //
    }

    public function review(User $user, Project $project){
        $userRoles = $user->roles;
    }
}
