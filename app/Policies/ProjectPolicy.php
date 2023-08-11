<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\Project;
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
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === '*' || $role->action === 'read');
        });

        return $hasCostEstimateUpdatePermission;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Project $project)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check project access
        if (!$this->projectAccess($user, $project)) {
            return false;
        }

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === '*' || $role->action === 'read');
        });

        return $hasCostEstimateUpdatePermission;
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
                ($role->action === '*' || $role->action === 'create');
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

        // Check project access
        if (!$this->projectAccess($user, $project)) {
            return false;
        }

        // Check if the user has the required role
        $hasCostEstimateUpdatePermission = $user->roles->contains(function ($role) {
            return $role->feature === 'cost_estimate' &&
                ($role->action === '*' || $role->action === 'update');
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
                ($role->action === '*' || $role->action === 'delete');
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

    public function projectAccess(User $user, Project $project){
        $position = $user->profiles?->position;
        $access = ['project_manager','super_administrator','administrator'];
        if($position == 'design_civil_engineer'){
            return $user->id == $project->design_engineer_civil;
        }
        if($position == 'design_mechanical_engineer'){
            return $user->id == $project->design_engineer_mechanical;
        }
        if($position == 'design_electrical_engineer'){
            return $user->id == $project->design_engineer_electrical;
        }
        if($position == 'design_instrument_engineer'){
            return $user->id == $project->design_engineer_instrument;
        }
        if(in_array($position,$access)){
            return true;
        }

        return false;
    }
}
