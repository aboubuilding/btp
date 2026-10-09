<?php
namespace App\Domain\QHSE\Services;

use App\Domain\QHSE\Models\CauserieSecurite;
use App\Domain\Socle\Services\JournalService;

class CauserieService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data, ?int $userId = null): CauserieSecurite
    {
        $data['anime_par'] = $userId ?? auth()->id();
        $causerie = CauserieSecurite::create($data);
        $this->journal->log('causerie.creee', $causerie);
        return $causerie;
    }

    public function mettreAJour(CauserieSecurite $causerie, array $data): CauserieSecurite
    {
        $causerie->update($data);
        return $causerie->fresh();
    }

    public function supprimer(CauserieSecurite $causerie): bool
    {
        return $causerie->update(['etat' => 0]);
    }

    public function parProjet(int $projetId): \Illuminate\Support\Collection
    {
        return CauserieSecurite::where('projet_id', $projetId)->latest('date')->get();
    }
}