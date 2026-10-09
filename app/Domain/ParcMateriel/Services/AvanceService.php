<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\AvanceSalaire;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class AvanceService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data, ?int $userId = null): AvanceSalaire
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['statut'] = 'demande';
            $avance = AvanceSalaire::create($data);
            $this->journal->log('avance.creee', $avance);
            return $avance;
        });
    }

    public function approuver(AvanceSalaire $avance, int $userId): AvanceSalaire
    {
        if ($avance->statut !== 'demande') {
            throw new RegleGestionException('Avance déjà traitée.');
        }

        $avance->update([
            'statut'      => 'approuvee',
            'approuve_par'=> $userId,
            'approuve_le' => now(),
        ]);
        $this->journal->log('avance.approuvee', $avance);
        return $avance;
    }

    public function rembourser(AvanceSalaire $avance, float $montant): AvanceSalaire
    {
        $nouveauRembourse = (float) $avance->montant_rembourse + $montant;
        if ($nouveauRembourse > (float) $avance->montant) {
            throw new RegleGestionException('Montant remboursé supérieur au montant initial.');
        }

        $avance->update([
            'montant_rembourse' => $nouveauRembourse,
            'statut'            => $nouveauRembourse >= (float) $avance->montant ? 'remboursee' : $avance->statut,
        ]);

        return $avance;
    }

    public function solde(int $employeId): float
    {
        return (float) AvanceSalaire::where('employee_id', $employeId)
            ->where('statut', 'approuvee')
            ->sum(\DB::raw('montant - montant_rembourse'));
    }
}