<?php

namespace RomegaDigital\MultitenancyNovaTool\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Contracts\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->hasAnyRole([config('multitenancy.roles.super_admin')])) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Role $role)
    {
        return $role->name != config('multitenancy.roles.super_admin');
    }

    public function create()
    {
        return false;
    }

    public function update()
    {
        return false;
    }

    /**
     * Determine whether the user can attach a user to a role.
     *
     * @param \App\User $user
     * @param \App\user $model
     *
     * @return mixed
     */
    public function attachAnyUser(User $user, Role $model)
    {
        return true;
    }
}
