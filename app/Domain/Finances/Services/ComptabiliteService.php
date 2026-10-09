<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\{EcritureComptable, ExerciceFiscal};
use App\Domain\Finances\Repositories\EcritureComptableRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class ComptabiliteService
{
    public function __construct(
        private EcritureComptableRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    /**
     * RG-K01 (Σ débit = Σ crédit) + RG-K02 (exercice non clôturé).
     */
    public function enregistrer(array $data, array $lignes): EcritureComptable
    {
        $totalDebit  = collect($lignes)->sum('debit');
        $totalCredit = collect($lignes)->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new RegleGestionException('Écriture déséquilibrée : Σ débit ≠ Σ crédit (RG-K01).');
        }

        $exercice = ExerciceFiscal::findOrFail($data['exercice_fiscal_id']);
        if ($exercice->statut === 'cloture') {
            throw new RegleGestionException('Exercice clôturé — aucune écriture possible (RG-K02).');
        }

        return DB::transaction(function () use ($data, $lignes) {
            $ecriture = $this->repo->create($data);

            foreach ($lignes as $ligne) {
                $ecriture->lignes()->create($ligne);
            }

            $this->journal->log('ecriture.creee', $ecriture);
            return $ecriture->fresh(['lignes']);
        });
    }

    public function valider(EcritureComptable $ecriture, int $userId): EcritureComptable
    {
        if ($ecriture->statut === 'validee') {
            throw new RegleGestionException('Écriture déjà validée.');
        }

        if (!$ecriture->est_equilibree) {
            throw new RegleGestionException('Écriture déséquilibrée (RG-K01).');
        }

        $ecriture->update([
            'statut'     => 'validee',
            'valide_par' => $userId,
            'valide_le'  => now(),
        ]);

        $this->journal->log('ecriture.validee', $ecriture);
        return $ecriture;
    }

    public function equilibrerEcriture(array $lignes): bool
    {
        return round(collect($lignes)->sum('debit'), 2) === round(collect($lignes)->sum('credit'), 2);
    }

    public function balance(int $exerciceId): \Illuminate\Support\Collection
    {
        return $this->repo->balance($exerciceId);
    }

    public function grandLivre(int $compteId, int $exerciceId): \Illuminate\Support\Collection
    {
        return $this->repo->grandLivre($compteId, $exerciceId);
    }

    public function statistiques(?int $exerciceId = null): array
    {
        return $this->repo->statistiques($exerciceId);
    }
}