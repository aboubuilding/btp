<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Domain\Finances\Models\ExerciceFiscal;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class ExerciceFiscalController extends Controller
{
    public function index()
    {
        $exercices = ExerciceFiscal::latest('date_debut')->get();
        return view('finances.exercices.index', compact('exercices'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', ExerciceFiscal::class);
        $data = $request->validate([
            'libelle'    => ['required', 'string', 'max:100'],
            'date_debut' => ['required', 'date'],
            'date_fin'   => ['required', 'date', 'after:date_debut'],
        ]);
        ExerciceFiscal::create(array_merge($data, ['statut' => 'ouvert']));
        return back()->with('success', 'Exercice créé.');
    }

    public function cloturer(ExerciceFiscal $exercice)
    {
        $this->authorize('cloturer', $exercice);
        try {
            if ($exercice->ecritures()->where('statut', 'brouillon')->exists()) {
                throw new RegleGestionException('Impossible de clôturer : écritures en brouillon.');
            }
            $exercice->update(['statut' => 'cloture']);
            return back()->with('success', 'Exercice clôturé.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}