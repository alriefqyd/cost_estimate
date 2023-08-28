<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'read');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'read');
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
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'create');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     */
    public function update(User $user)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'update');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'delete');
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        // Eager load roles to minimize database queries
        $user->load('roles');

        // Check if the user has the required role
        $hasPermission = $user->roles->contains(function ($role) {
            return $role->feature === 'user' &&
                ($role->action === '*' || $role->action === 'delete');
        });

        return $hasPermission;
    }
}
