<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\{Projet, Tache};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class TacheService
{
    public function __construct(
        private JournalService $journal,
    ) {}

    public function creer(Projet $projet, array $data): Tache
    {
        return DB::transaction(function () use ($projet, $data) {
            $data['projet_id'] = $projet->id;

            if (!empty($data['date_debut']) && !empty($data['date_fin'])) {
                $this->validerDates($projet, $data);
            }

            $tache = Tache::create($data);
            $this->journal->log('tache.creee', $tache);
            return $tache;
        });
    }

    public function mettreAJour(Tache $tache, array $data): Tache
    {
        return DB::transaction(function () use ($tache, $data) {
            $data = array_merge($tache->toArray(), $data);
            $this->validerDates($tache->projet, $data);

            $tache->update($data);
            $this->journal->log('tache.modifiee', $tache);
            return $tache->fresh();
        });
    }

    public function mettreAJourAvancement(Tache $tache, int $avancement): Tache
    {
        $tache->update(['pourcentage_avancement' => min(100, max(0, $avancement))]);

        if ($tache->pourcentage_avancement === 100) {
            $tache->update(['statut' => 'termine']);
        } elseif ($tache->pourcentage_avancement > 0 && $tache->statut === 'a_faire') {
            $tache->update(['statut' => 'en_cours']);
        }

        return $tache->fresh();
    }

    public function supprimer(Tache $tache): bool
    {
        return DB::transaction(function () use ($tache) {
            $tache->dependances()->delete();
            $this->journal->log('tache.supprimee', $tache);
            return $tache->update(['etat' => 0]);
        });
    }

    private function validerDates(Projet $projet, array $data): void
    {
        if (empty($data['date_debut']) || empty($data['date_fin'])) return;

        $debut = \Carbon\Carbon::parse($data['date_debut']);
        $fin   = \Carbon\Carbon::parse($data['date_fin']);

        if ($fin->lt($debut)) {
            throw new RegleGestionException('La date de fin doit être après la date de début.');
        }

        if ($projet->date_debut_prevue && $debut->lt($projet->date_debut_prevue)) {
            throw new RegleGestionException(
                'La date de début de la tâche doit être comprise dans la période du chantier.'
            );
        }

        if ($projet->date_fin_prevue && $fin->gt($projet->date_fin_prevue)) {
            throw new RegleGestionException(
                'La date de fin de la tâche doit être comprise dans la période du chantier.'
            );
        }
    }
}