<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if ($request->user()?->hasAnyRole($roles)) {
            return $next($request);
        }

        return to_route('dashboard')
            ->with('error', 'Você não tem permissão para entrar nesta seção.');
    }
}
