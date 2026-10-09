<?php
namespace App\Domain\QHSE\Services;

use App\Domain\QHSE\Models\NonConformite;
use App\Domain\Socle\Services\JournalService;

class NonConformiteService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data): NonConformite
    {
        $data['statut'] = 'ouverte';
        $nc = NonConformite::create($data);
        $this->journal->log('non_conformite.creee', $nc);
        return $nc;
    }

    public function mettreAJour(NonConformite $nc, array $data): NonConformite
    {
        $nc->update($data);
        return $nc->fresh();
    }

    public function mettreEnTraitement(NonConformite $nc): NonConformite
    {
        return $nc->update(['statut' => 'en_traitement']) ? $nc->fresh() : $nc;
    }

    public function lever(NonConformite $nc): NonConformite
    {
        $nc->update([
            'statut'     => 'levee',
            'date_levee' => now(),
        ]);
        $this->journal->log('non_conformite.levee', $nc);
        return $nc;
    }
}