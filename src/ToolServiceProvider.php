<?php

namespace RomegaDigital\MultitenancyNovaTool;

use Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RomegaDigital\MultitenancyNovaTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'multitenancy-nova-tool');

        $this->app->booted(function () {
            $this->routes();
        });

        // Gate::policy(config('multitenancy.tenant_model'), PermissionPolicy::class);
        // Gate::policy(config('multitenancy.user_model'), UserPolicy::class);

        Nova::serving(function (ServingNova $event) {
            //
        });
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

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/multitenancy-nova-tool')
                ->group(__DIR__.'/../routes/api.php');
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
