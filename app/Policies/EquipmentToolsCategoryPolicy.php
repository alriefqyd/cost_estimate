<?php

namespace App\Policies;

use App\Models\EquipmentToolsCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentToolsCategoryPolicy
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
        $userRoles = $user->roles;
        foreach($userRoles as $role){
            if($role->feature == 'tool_equipment'){
                if($role->action == '*' || $role->action == 'read'){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentToolsCategory  $equipmentToolsCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EquipmentToolsCategory $equipmentToolsCategory)
    {
        $userRoles = $user->roles;
        foreach($userRoles as $role){
            if($role->feature == 'tool_equipment'){
                if($role->action == '*' || $role->action == 'read'){
                    return true;
                }
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
        $userRoles = $user->roles;
        foreach($userRoles as $role){
            if($role->feature == 'tool_equipment'){
                if($role->action == '*' || $role->action == 'create'){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentToolsCategory  $equipmentToolsCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $userRoles = $user->roles;
        foreach($userRoles as $role){
            if($role->feature == 'tool_equipment'){
                if($role->action == '*' || $role->action == 'update'){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentToolsCategory  $equipmentToolsCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        $userRoles = $user->roles;
        foreach($userRoles as $role){
            if($role->feature == 'tool_equipment'){
                if($role->action == '*' || $role->action == 'delete'){
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentToolsCategory  $equipmentToolsCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EquipmentToolsCategory $equipmentToolsCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentToolsCategory  $equipmentToolsCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EquipmentToolsCategory $equipmentToolsCategory)
    {
        //
    }
}
