<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\PlanComptable;
use App\Domain\Socle\Services\JournalService;

class PlanComptableService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data): PlanComptable
    {
        $compte = PlanComptable::create($data);
        $this->journal->log('plan_comptable.cree', $compte);
        return $compte;
    }

    public function mettreAJour(PlanComptable $compte, array $data): PlanComptable
    {
        $compte->update($data);
        return $compte->fresh();
    }

    public function parClasse(string $classe): \Illuminate\Support\Collection
    {
        return PlanComptable::where('classe', $classe)->where('etat', 1)->orderBy('numero')->get();
    }

    public function tousActifs(): \Illuminate\Support\Collection
    {
        return PlanComptable::where('etat', 1)->orderBy('numero')->get();
    }
}