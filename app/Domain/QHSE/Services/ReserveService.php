<?php
namespace App\Domain\QHSE\Services;

use App\Domain\QHSE\Models\Reserve;
use App\Domain\QHSE\Models\PvReception;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Collection;

class ReserveService
{
    public function __construct(private JournalService $journal) {}

    public function creer(PvReception $pv, array $data): Reserve
    {
        $reserve = $pv->reserves()->create(array_merge($data, ['statut' => 'ouverte']));
        $this->journal->log('reserve.creee', $reserve);
        return $reserve;
    }

    public function mettreAJour(Reserve $reserve, array $data): Reserve
    {
        $reserve->update($data);
        return $reserve->fresh();
    }

    public function lever(Reserve $reserve, ?int $userId = null): Reserve
    {
        if ($reserve->est_levee) {
            throw new RegleGestionException('Réserve déjà levée.');
        }

        $reserve->update([
            'statut'     => 'levee',
            'date_levee' => now(),
        ]);

        $this->journal->log('reserve.levee', $reserve);
        return $reserve;
    }

    public function enRetard(): Collection
    {
        return Reserve::enRetard()->with('pv.projet')->get();
    }
}