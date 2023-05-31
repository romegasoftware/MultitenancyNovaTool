<?php

namespace RomegaDigital\MultitenancyNovaTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Illuminate\Support\Facades\Auth;

class MultitenancyNovaTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::resources([
            Tenant::class,
        ]);
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('multitenancy-tool::navigation');
    }
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        if (Auth::user()->can('viewAny', app(\RomegaDigital\Multitenancy\Multitenancy::class)->getTenantClass())){
            return MenuSection::make('Multitenancy')
                ->path('/resources/tenants')
                ->icon('user-group');
        }
    }
}
