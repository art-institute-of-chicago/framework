<?php

namespace Aic\Hub\Foundation\Middleware;

use Closure;

class ForceAcceptJson
{
    /**
     * Make `wantsJson` return true. Our API should always return JSON.
     * Symphony's `HeaderBag` forces header names to lowercase.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
