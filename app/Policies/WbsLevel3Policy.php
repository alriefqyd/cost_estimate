<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkBreakdownStructure;
use Illuminate\Auth\Access\HandlesAuthorization;

class WbsLevel3Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'wbs' &&
                ($role->action === 'read');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBreakdownStructure  $workBreakdownStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, WorkBreakdownStructure $workBreakdownStructure)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'wbs' &&
                ($role->action === 'read');
        });

        return $hasPermission;
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
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'wbs' &&
                ($role->action === 'create');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBreakdownStructure  $workBreakdownStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'wbs' &&
                ($role->action === 'update');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBreakdownStructure  $workBreakdownStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, WorkBreakdownStructure $workBreakdownStructure)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'wbs' &&
                ($role->action === 'delete');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBreakdownStructure  $workBreakdownStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, WorkBreakdownStructure $workBreakdownStructure)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBreakdownStructure  $workBreakdownStructure
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, WorkBreakdownStructure $workBreakdownStructure)
    {
        //
    }
}
