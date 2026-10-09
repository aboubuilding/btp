<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Domain\Personnel\Models\{Departement, Poste, TypeConge};

class ReferentielController extends Controller
{
    public function index()
    {
        return view('rh.referentiels.index', [
            'departements' => Departement::where('etat', 1)->orderBy('nom')->get(),
            'postes'       => Poste::with('departement')->where('etat', 1)->orderBy('nom')->get(),
            'typesConges'  => TypeConge::where('etat', 1)->orderBy('nom')->get(),
        ]);
    }
}