<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\{Projet, AvancementProjet};
use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class AvancementService
{
    public function __construct(
        private ProjetRepositoryInterface $projetRepo,
        private JournalService $journal,
    ) {}

    public function enregistrer(Projet $projet, array $data, int $userId): AvancementProjet
    {
        return DB::transaction(function () use ($projet, $data, $userId) {
            $avancement = AvancementProjet::create(array_merge($data, [
                'projet_id' => $projet->id,
                'saisi_par' => $userId,
            ]));

            // Met à jour l'avancement global du projet
            $projet->update([
                'pourcentage_avancement' => $data['pourcentage_avancement'],
            ]);

            $this->journal->log('avancement.enregistre', $avancement);
            return $avancement;
        });
    }

    public function valider(AvancementProjet $avancement, int $userId): AvancementProjet
    {
        $avancement->update([
            'valide_par' => $userId,
            'valide_le'  => now(),
        ]);
        $this->journal->log('avancement.valide', $avancement);
        return $avancement;
    }

    /**
     * Avancement pondéré par montant des lignes DQE (RG-E05).
     */
    public function calculerAvancementPondere(Projet $projet): float
    {
        return $projet->avancement_pondere;
    }

    public function recalculerEtSauvegarder(Projet $projet): float
    {
        $avancement = $this->calculerAvancementPondere($projet);
        $this->projetRepo->update($projet, ['pourcentage_avancement' => (int) $avancement]);
        return $avancement;
    }
}