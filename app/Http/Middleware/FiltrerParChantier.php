<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FiltrerParChantier
{
    public function handle(Request $request, Closure $next)
    {
        // Ajoute le filtre chantier par défaut dans la requête
        $user = $request->user();
        if ($user && $user->hasRole('conducteur_travaux', 'chef_chantier')) {
            $request->merge(['_perimetre_chantier' => true]);
        }
        return $next($request);
    }
}