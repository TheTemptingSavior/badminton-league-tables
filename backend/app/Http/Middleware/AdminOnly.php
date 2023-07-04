<?php

namespace App\Http\Middleware;

use Closure;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request Lumen request object
     * @param  \Closure  $next The next middleware to run
     * @param  string|null  $guard Any guard conditions
     * @return mixed Returns the next middleware in the chain
     */
    public function handle($request, Closure $next, $guard = null): mixed
    {
        if (auth()->user()->admin != true) {
            return response()->json(
                ['error' => 'Forbidden', 'message' => 'Must be admin to perform this operation'],
                403
            );
        }

        return $next($request);
    }
}
