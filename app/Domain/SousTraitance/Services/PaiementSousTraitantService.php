<?php
namespace App\Domain\SousTraitance\Services;

use App\Domain\SousTraitance\Models\{ContratSousTraitant, FactureSousTraitant, PaiementSousTraitant};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class PaiementSousTraitantService
{
    public function __construct(private JournalService $journal) {}

    /**
     * RG-T01 : plafond de paiement = montant actualisé - retenue garantie.
     */
    public function enregistrer(ContratSousTraitant $contrat, array $data): PaiementSousTraitant
    {
        return DB::transaction(function () use ($contrat, $data) {
            $plafond = $contrat->plafond_paiement;
            $totalApres = $contrat->total_paye + (float) $data['montant'];

            if ($totalApres > $plafond) {
                throw new RegleGestionException(sprintf(
                    'Plafond dépassé (RG-T01). Plafond : %s · Reste : %s',
                    number_format($plafond, 0, ',', ' '),
                    number_format($plafond - $contrat->total_paye, 0, ',', ' ')
                ));
            }

            // Récupère la facture ouverte
            $facture = FactureSousTraitant::where('contrat_sous_traitant_id', $contrat->id)
                ->whereIn('statut', ['recue', 'validee'])
                ->orderBy('date_facture')
                ->first();

            if (!$facture) {
                throw new RegleGestionException('Aucune facture ouverte pour ce contrat.');
            }

            $paiement = $facture->paiements()->create($data);

            // Si la facture est entièrement payée
            if ($facture->fresh()->est_payee) {
                $facture->update(['statut' => 'payee']);
            }

            $this->journal->log('paiement_st.enregistre', $paiement);
            return $paiement;
        });
    }
}