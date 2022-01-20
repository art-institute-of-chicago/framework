<?php

namespace Aic\Hub\Foundation\Middleware;

use Closure;

class ETagMiddleware
{
    /**
     * Implement Etag support
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('get')) {
            $etag = md5($response->getContent());
            $requestEtag = str_replace('"', '', $request->getETags());

            if ($requestEtag && $requestEtag[0] == $etag) {
                $response->setNotModified();
            }

            $response->setEtag($etag);
        }

        return $response;
    }
}
