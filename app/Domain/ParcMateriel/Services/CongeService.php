<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\DemandeConge;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class CongeService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data, ?int $userId = null): DemandeConge
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['statut'] = 'demande';
            $conge = DemandeConge::create($data);

            $this->journal->log('conge.demande', $conge);
            return $conge;
        });
    }

    public function approuver(DemandeConge $conge, int $userId): DemandeConge
    {
        if ($conge->statut !== 'demande') {
            throw new RegleGestionException('Congé déjà traité.');
        }

        $conge->update([
            'statut'      => 'approuve',
            'approuve_par'=> $userId,
            'approuve_le' => now(),
        ]);

        $this->journal->log('conge.approuve', $conge);
        return $conge;
    }

    public function refuser(DemandeConge $conge, int $userId): DemandeConge
    {
        $conge->update([
            'statut'      => 'refuse',
            'approuve_par'=> $userId,
            'approuve_le' => now(),
        ]);
        return $conge;
    }

    public function soldeConges(int $employeId): int
    {
        return (int) DemandeConge::where('employee_id', $employeId)
            ->where('statut', 'approuve')
            ->whereYear('date_debut', now()->year)
            ->sum('nombre_jours');
    }
}