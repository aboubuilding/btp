<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\NiveauStock;

class CmupService
{
    /**
     * Calcule le nouveau CMUP après entrée.
     * Formule : (Q × CMUP + q × p) / (Q + q)
     */
    public function calculer(float $quantiteAncienne, float $cmupAncien, float $quantiteEntree, float $prixUnitaire): float
    {
        $total = $quantiteAncienne + $quantiteEntree;
        if ($total <= 0) return $prixUnitaire;
        return round((($quantiteAncienne * $cmupAncien) + ($quantiteEntree * $prixUnitaire)) / $total, 2);
    }

    public function calculerDepuisNiveau(NiveauStock $niveau, float $quantiteEntree, float $prixUnitaire): float
    {
        return $this->calculer(
            (float) $niveau->quantite,
            (float) $niveau->cmup,
            $quantiteEntree,
            $prixUnitaire
        );
    }

    public function valeurTotale(): float
    {
        return (float) NiveauStock::sum(\DB::raw('quantite * cmup'));
    }
}