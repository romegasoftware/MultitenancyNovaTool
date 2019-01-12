<?php

namespace RomegaDigital\MultitenancyNovaTool\Http\Middleware;

use RomegaDigital\MultitenancyNovaTool\MultitenancyNovaTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(MultitenancyNovaTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
