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
                new \Vyuldashev\NovaPermission\NovaPermissionTool(),
            ]);
        });

        Gate::policy(config('multitenancy.tenant_model'), TenantPolicy::class);
        Gate::policy(config('permission.models.permission'), PermissionPolicy::class);
        Gate::policy(config('permission.models.role'), RolePolicy::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
