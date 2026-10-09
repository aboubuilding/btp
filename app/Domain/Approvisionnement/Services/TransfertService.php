<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\TransfertStock;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class TransfertService
{
    public function __construct(
        private StockService $stockService,
        private JournalService $journal,
    ) {}

    public function demander(array $data, int $userId): TransfertStock
    {
        if ($data['entrepot_source_id'] === $data['entrepot_destination_id']) {
            throw new RegleGestionException('Les dépôts source et destination doivent différer.');
        }

        return DB::transaction(function () use ($data, $userId) {
            $transfert = TransfertStock::create(array_merge($data, [
                'statut'      => 'en_attente',
                'demande_par' => $userId,
            ]));

            $this->journal->log('transfert.demande', $transfert);
            return $transfert;
        });
    }

    public function approuver(TransfertStock $transfert, int $userId): TransfertStock
    {
        if ($transfert->statut !== 'en_attente') {
            throw new RegleGestionException('Transfert déjà traité.');
        }

        return DB::transaction(function () use ($transfert, $userId) {
            $this->stockService->transferer(
                $transfert->entrepot_source_id,
                $transfert->entrepot_destination_id,
                $transfert->materiau_id,
                $transfert->quantite
            );

            $transfert->update([
                'statut'       => 'approuve',
                'approuve_par' => $userId,
                'approuve_le'  => now(),
            ]);

            $this->journal->log('transfert.approuve', $transfert);
            return $transfert;
        });
    }

    public function rejeter(TransfertStock $transfert, int $userId, ?string $motif = null): TransfertStock
    {
        $transfert->update([
            'statut'       => 'rejete',
            'approuve_par' => $userId,
            'approuve_le'  => now(),
        ]);
        $this->journal->log('transfert.rejete', $transfert, $userId, null, ['motif' => $motif]);
        return $transfert;
    }
}