<?php
namespace App\Domain\SousTraitance\Services;

use App\Domain\SousTraitance\Models\FactureSousTraitant;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class FactureSousTraitantService
{
    public function __construct(private JournalService $journal) {}

    public function creer(array $data): FactureSousTraitant
    {
        return DB::transaction(function () use ($data) {
            $data['statut'] = $data['statut'] ?? 'recue';
            $facture = FactureSousTraitant::create($data);
            $this->journal->log('facture_st.creee', $facture);
            return $facture;
        });
    }

    public function valider(FactureSousTraitant $facture): FactureSousTraitant
    {
        $facture->update(['statut' => 'validee']);
        return $facture;
    }

    public function marquerPayee(FactureSousTraitant $facture): FactureSousTraitant
    {
        $facture->update(['statut' => 'payee']);
        return $facture;
    }
}