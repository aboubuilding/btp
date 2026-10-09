<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\PeriodePaieCloturee;
use App\Domain\Personnel\Models\Presence;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImputerMainOeuvreChantiers implements ShouldQueue
{
    public string $queue = 'paie';

    public function __construct(private JournalService $journal) {}

    public function handle(PeriodePaieCloturee $event): void
    {
        $periode = $event->periode;

        $parProjet = Presence::validees()
            ->whereBetween('date', [$periode->date_debut, $periode->date_fin])
            ->select('projet_id', DB::raw('SUM(heures_travaillees) as total_heures'))
            ->whereNotNull('projet_id')
            ->groupBy('projet_id')
            ->get();

        if ($parProjet->isEmpty()) return;

        $coutMoyen = (float) \App\Domain\Personnel\Models\Employe::where('statut', 'actif')->avg('salaire_base') / 173;

        foreach ($parProjet as $ligne) {
            $projet = \App\Domain\Execution\Models\Projet::find($ligne->projet_id);
            if (!$projet) continue;

            $coutMainOeuvre = round($ligne->total_heures * $coutMoyen, 2);

            $projet->increment('budget_reel', $coutMainOeuvre);

            $ligneBudget = $projet->ligneBudgets()->where('libelle', "Main d'œuvre")->first();
            $ligneBudget?->increment('montant_reel', $coutMainOeuvre);

            $this->journal->log('projet.cout_main_oeuvre_impute', $projet, null, null, [
                'periode_id'      => $periode->id,
                'heures'          => $ligne->total_heures,
                'cout_impute'     => $coutMainOeuvre,
            ]);
        }

        Log::info('[Listener] Main d\'œuvre imputée aux chantiers', [
            'periode_id'    => $periode->id,
            'nb_projets'    => $parProjet->count(),
        ]);
    }
}