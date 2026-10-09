<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\Caisse;
use App\Domain\Socle\Services\JournalService;

class CaisseService
{
    public function __construct(
        private JournalService $journal,
    ) {}

    public function creer(array $data): Caisse
    {
        $data['solde_actuel'] = $data['solde_initial'] ?? 0;
        $caisse = Caisse::create($data);
        $this->journal->log('caisse.creee', $caisse);
        return $caisse;
    }

    public function mettreAJour(Caisse $caisse, array $data): Caisse
    {
        $caisse->update($data);
        return $caisse->fresh();
    }

    public function desactiver(Caisse $caisse): bool
    {
        return $caisse->update(['etat' => 0]);
    }

    public function alimenter(Caisse $caisse, float $montant): Caisse
    {
        $caisse->increment('solde_actuel', $montant);
        $this->journal->log('caisse.alimentee', $caisse, null, null, ['montant' => $montant]);
        return $caisse->fresh();
    }
}