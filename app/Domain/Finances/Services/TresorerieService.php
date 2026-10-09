<?php
namespace App\Domain\Finances\Services;

use App\Domain\Finances\Models\{Caisse, CompteBancaire, Paiement};
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Support\Facades\DB;

class TresorerieService
{
    /**
     * RG-K03 : mise à jour du solde de caisse.
     */
    public function mettreAJourSoldeCaisse(Caisse $caisse, float $montant, string $sens): void
    {
        $nouveauSolde = $sens === 'encaissement'
            ? (float) $caisse->solde_actuel + $montant
            : (float) $caisse->solde_actuel - $montant;

        if ($nouveauSolde < 0) {
            throw new RegleGestionException('Solde de caisse insuffisant (RG-K03).');
        }

        $caisse->update(['solde_actuel' => $nouveauSolde]);
    }

    public function mettreAJourSoldeBancaire(CompteBancaire $compte, float $montant, string $sens): void
    {
        $nouveauSolde = $sens === 'encaissement'
            ? (float) $compte->solde_actuel + $montant
            : (float) $compte->solde_actuel - $montant;

        $compte->update(['solde_actuel' => $nouveauSolde]);
    }

    public function enregistrerPaiement(Paiement $paiement): void
    {
        DB::transaction(function () use ($paiement) {
            if ($paiement->caisse_id) {
                $caisse = Caisse::lockForUpdate()->find($paiement->caisse_id);
                $this->mettreAJourSoldeCaisse($caisse, (float) $paiement->montant, $paiement->sens);
            }

            if ($paiement->compte_bancaire_id) {
                $compte = CompteBancaire::lockForUpdate()->find($paiement->compte_bancaire_id);
                $this->mettreAJourSoldeBancaire($compte, (float) $paiement->montant, $paiement->sens);
            }
        });
    }

    public function soldeGlobal(): float
    {
        return (float) Caisse::sum('solde_actuel') + (float) CompteBancaire::sum('solde_actuel');
    }

    public function positionTresorerie(): array
    {
        return [
            'total_caisses'   => (float) Caisse::sum('solde_actuel'),
            'total_banques'   => (float) CompteBancaire::sum('solde_actuel'),
            'solde_global'    => $this->soldeGlobal(),
            'caisses'         => Caisse::where('etat', 1)->get(['libelle', 'solde_actuel']),
            'comptes'         => CompteBancaire::where('etat', 1)->get(['libelle', 'banque', 'solde_actuel']),
        ];
    }
}