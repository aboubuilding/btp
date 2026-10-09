<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifierRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user() || !$request->user()->hasRole(...$roles)) {
            abort(403, 'Accès refusé. Rôles requis : ' . implode(', ', $roles));
        }
        return $next($request);
    }
}