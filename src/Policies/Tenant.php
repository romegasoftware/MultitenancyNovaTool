<?php

namespace RomegaDigital\MultitenancyNovaTool\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Tenant
{
    use HandlesAuthorization;

    /**
     * Allow nothing by default; gate within multitenancy package
     * will allow users who can 'access admin'.
     */
}
