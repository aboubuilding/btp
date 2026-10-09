<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\PaiementEnregistre;
use App\Domain\Finances\Models\{Caisse, CompteBancaire};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MettreAJourSoldeCaisseOuBanque
{
    public function __construct(private JournalService $journal) {}

    public function handle(PaiementEnregistre $event): void
    {
        $paiement = $event->paiement;

        DB::transaction(function () use ($paiement) {
            if ($paiement->caisse_id) {
                $caisse = Caisse::lockForUpdate()->find($paiement->caisse_id);
                if ($caisse) {
                    $nouveauSolde = $paiement->sens === 'encaissement'
                        ? (float) $caisse->solde_actuel + (float) $paiement->montant
                        : (float) $caisse->solde_actuel - (float) $paiement->montant;

                    if ($nouveauSolde < 0) {
                        throw new RegleGestionException('Solde de caisse insuffisant (RG-K03).');
                    }

                    $caisse->update(['solde_actuel' => $nouveauSolde]);
                    $this->journal->log('caisse.solde_maj', $caisse, null, null, [
                        'paiement_id' => $paiement->id,
                        'nouveau'     => $nouveauSolde,
                    ]);
                }
            }

            if ($paiement->compte_bancaire_id) {
                $compte = CompteBancaire::lockForUpdate()->find($paiement->compte_bancaire_id);
                if ($compte) {
                    $nouveauSolde = $paiement->sens === 'encaissement'
                        ? (float) $compte->solde_actuel + (float) $paiement->montant
                        : (float) $compte->solde_actuel - (float) $paiement->montant;

                    $compte->update(['solde_actuel' => $nouveauSolde]);
                }
            }
        });

        Log::info('[Listener] Soldes mis à jour', ['paiement_id' => $paiement->id]);
    }
}