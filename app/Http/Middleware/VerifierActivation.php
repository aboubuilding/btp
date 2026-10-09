<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifierActivation
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && !$request->user()->est_actif) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Votre compte a été désactivé.');
        }
        return $next($request);
    }
}