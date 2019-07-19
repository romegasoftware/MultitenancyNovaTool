<?php

namespace RomegaDigital\MultitenancyNovaTool;

use Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use RomegaDigital\MultitenancyNovaTool\Http\Middleware\Authorize;
use RomegaDigital\MultitenancyNovaTool\Policies\PermissionPolicy;
use RomegaDigital\MultitenancyNovaTool\Policies\RolePolicy;
use RomegaDigital\MultitenancyNovaTool\Policies\TenantPolicy;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'multitenancy-tool');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::tools([
                \Vyuldashev\NovaPermission\NovaPermissionTool::make()
                ->roleResource(config('multitenancy.custom_resources.role') ?: \Vyuldashev\NovaPermission\Role::class)
                ->permissionResource(config('multitenancy.custom_resources.permission') ?: \Vyuldashev\NovaPermission\Permission::class),
            ]);
        });

        Gate::policy(config('multitenancy.tenant_model'), TenantPolicy::class);
        Gate::policy(config('permission.models.permission'), PermissionPolicy::class);
        Gate::policy(config('permission.models.role'), RolePolicy::class);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        // Route::middleware(['nova', Authorize::class])
        //         ->prefix('nova-vendor/multitenancy-tool')
        //         ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
