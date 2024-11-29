<?php

namespace App\Policies;

use App\Models\ManPower;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManPowerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    { // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
                ($role->action === 'read');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManPower  $manPower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
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
            return $role->feature === 'man_power' &&
                ($role->action === 'create');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManPower  $manPower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
                ($role->action === 'update');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManPower  $manPower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
                ($role->action === 'delete');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManPower  $manPower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManPower $manPower)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManPower  $manPower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ManPower $manPower)
    {
        //
    }

    /**
     * Determine whether the user can import the xlsx file
     * @param User $user
     * @return mixed
     */
    public function import(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
                ($role->action === 'import');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can import the xlsx file
     * @param User $user
     * @return mixed
     */
    public function export(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'man_power' &&
                ($role->action === 'export');
        });

        return $hasPermission;
    }
}
