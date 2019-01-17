<?php

namespace RomegaDigital\MultitenancyNovaTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

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
}
