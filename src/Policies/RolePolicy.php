<?php

namespace RomegaDigital\MultitenancyNovaTool\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Contracts\Role;

class RolePolicy
{
    use HandlesAuthorization;


    /**
     * Allow a super administrator to take all actions.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return bool
     */
    public function before(Authenticatable $user)
    {
        if ($user->hasAnyRole([config('multitenancy.roles.super_admin')])) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return bool
     */
    public function viewAny(Authenticatable $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view a role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param \Spatie\Permission\Contracts\Role $role
     *
     * @return bool
     */
    public function view(Authenticatable $user, Role $role)
    {
        // Don't allow users to see the Super Admin role.
        return $role->name != config('multitenancy.roles.super_admin');
    }

    /**
     * Determine whether the user can create a role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return bool
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can update a role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return bool
     */
    public function update()
    {
        return false;
    }

    /**
     * Determine whether the user can attach a user to a role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param \Spatie\Permission\Contracts\Role $role
     *
     * @return bool
     */
    public function attachAnyUser(Authenticatable $user, Role $role)
    {
        return true;
    }
}
