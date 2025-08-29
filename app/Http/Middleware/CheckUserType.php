<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$types One or more allowed user_type values
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (auth()->check() && in_array(auth()->user()->user_type, $types)) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}
