<?php

namespace App\Http\Middleware;

use Closure;

class JsonHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request Lumen request object
     * @param  \Closure  $next Next middleware to run
     * @return mixed Returns the next middleware in the chain
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set("Content-Type", "application/json");

        return $response;
    }
}
