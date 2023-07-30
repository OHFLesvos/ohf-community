<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->secure() && (config('app.env') === 'prod' || config('app.env') === 'production')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
