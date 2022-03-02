<?php

namespace Aic\Hub\Foundation\Middleware;

use Closure;

class BasicAuthMiddleware
{
    /**
     * Adapted from the linked tutorial by Stefan Zweifel.
     *
     * @link https://stefanzweifel.io/posts/use-basic-authentication-to-protect-the-laravel-horizon-dashboard
     */
    public function handle($request, Closure $next)
    {
        $configuredUsername = config('aic.basic_auth.username');
        $configuredPassword = config('aic.basic_auth.password');

        if (empty($configuredUsername) || empty($configuredPassword)) {
            return $next($request);
        }

        $authenticationHasPassed = false;

        if ($request->header('PHP_AUTH_USER', null) && $request->header('PHP_AUTH_PW', null)) {
            $username = $request->header('PHP_AUTH_USER');
            $password = $request->header('PHP_AUTH_PW');

            if ($username === $configuredUsername && $password === $configuredPassword) {
                $authenticationHasPassed = true;
            }
        }

        if ($authenticationHasPassed === false) {
            return response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
