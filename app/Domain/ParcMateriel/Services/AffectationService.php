<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\{Equipement, AffectationEquipement};
use App\Domain\ParcMateriel\Repositories\EquipementRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class AffectationService
{
    public function __construct(
        private EquipementRepositoryInterface $equipementRepo,
        private JournalService $journal,
    ) {}

    /**
     * RG-M01, RG-M02, RG-M04.
     */
    public function affecter(Equipement $equipement, int $projetId, array $data, ?int $userId = null): AffectationEquipement
    {
        if ($equipement->statut !== 'disponible') {
            throw new RegleGestionException('Engin non disponible (RG-M01).');
        }
        if ($equipement->affectationOuverte()->exists()) {
            throw new RegleGestionException('Engin déjà affecté à un chantier (RG-M01).');
        }

        // RG-M04 : assurance non expirée
        if ($equipement->assurance_expiree) {
            throw new RegleGestionException('Assurance expirée — affectation impossible (RG-M04).');
        }

        // RG-M02 : compteur croissant
        if (isset($data['compteur_debut']) && (float) $data['compteur_debut'] < (float) $equipement->compteur_heures_actuel) {
            throw new RegleGestionException('Compteur décroissant interdit (RG-M02).');
        }

        return DB::transaction(function () use ($equipement, $projetId, $data, $userId) {
            $affectation = $equipement->affectations()->create(array_merge($data, [
                'projet_id'      => $projetId,
                'date_debut'     => $data['date_debut'] ?? now()->toDateString(),
                'compteur_debut' => $data['compteur_debut'] ?? $equipement->compteur_heures_actuel,
                'statut'         => 'ouverte',
                'valide_par'     => $userId,
            ]));

            $this->equipementRepo->update($equipement, [
                'statut'           => 'en_service',
                'projet_actuel_id' => $projetId,
            ]);

            $this->journal->log('equipement.affecte', $affectation, null, null, [
                'equipement_id' => $equipement->id,
                'projet_id'     => $projetId,
            ]);

            return $affectation;
        });
    }

    public function fermer(AffectationEquipement $affectation, ?float $compteurFin = null): AffectationEquipement
    {
        if (!$affectation->est_ouverte) {
            throw new RegleGestionException('Affectation déjà fermée.');
        }

        return DB::transaction(function () use ($affectation, $compteurFin) {
            $compteurFin = $compteurFin ?? $affectation->equipement->compteur_heures_actuel;

            $affectation->update([
                'statut'       => 'fermee',
                'date_fin'     => now(),
                'compteur_fin' => $compteurFin,
            ]);

            $affectation->equipement->update([
                'statut'                 => 'disponible',
                'projet_actuel_id'       => null,
                'compteur_heures_actuel' => $compteurFin,
            ]);

            $this->journal->log('affectation.fermee', $affectation);
            return $affectation->fresh();
        });
    }
}