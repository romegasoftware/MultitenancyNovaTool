<?php

namespace RomegaDigital\MultitenancyNovaTool\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class Tenant
{
    use HandlesAuthorization;

    /**
     * Checks if user is allowed to see the resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return void
     */
    public function viewAny($user)
    {
        return Cache::remember('tenant-policy:' . $user->id, 5, function () use ($user) {
            return $user->can('access admin');
        });
    }
}
