<?php

namespace RomegaDigital\MultitenancyNovaTool;

use Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use RomegaDigital\MultitenancyNovaTool\Policies\RolePolicy;
use RomegaDigital\MultitenancyNovaTool\Policies\TenantPolicy;
use RomegaDigital\MultitenancyNovaTool\Policies\PermissionPolicy;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'multitenancy-tool');

        Nova::serving(function (ServingNova $event) {
            Nova::tools([
                \Vyuldashev\NovaPermission\NovaPermissionTool::make()
                    ->rolePolicy(config('multitenancy.policies.role', RolePolicy::class))
                    ->permissionPolicy(config('multitenancy.policies.permission', PermissionPolicy::class))
                    ->roleResource(config('multitenancy.resources.role', \Vyuldashev\NovaPermission\Role::class))
                    ->permissionResource(config('multitenancy.resources.permission', \Vyuldashev\NovaPermission\Permission::class)),
            ]);
        });

        Gate::policy(config('multitenancy.tenant_model'), TenantPolicy::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
