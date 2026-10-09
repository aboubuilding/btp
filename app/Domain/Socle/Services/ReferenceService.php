<?php
namespace App\Domain\Socle\Services;

/**
 * Génère tous les numéros de référence métier.
 */
class ReferenceService
{
    public function __construct(private ParametreService $params) {}

    public function chantier(): string
    {
        return $this->generer('numero.prefixe_ch', 'CH', 3);
    }

    public function devis(): string
    {
        return $this->generer('numero.prefixe_devis', 'DEV', 4);
    }

    public function marche(): string
    {
        return $this->generer('numero.prefixe_mar', 'MAR', 4);
    }

    public function facture(): string
    {
        return $this->generer('numero.prefixe_fac', 'FAC', 4);
    }

    public function bonCommande(): string
    {
        return $this->generer('numero.prefixe_bc', 'BC', 4);
    }

    public function situation(): string
    {
        return $this->generer('numero.prefixe_sit', 'SIT', 4);
    }

    public function demandeAchat(): string
    {
        return $this->generer('numero.prefixe_da', 'DA', 4);
    }

    public function livraison(): string
    {
        return $this->generer('numero.prefixe_liv', 'LIV', 4);
    }

    public function paiement(): string
    {
        return $this->generer('numero.prefixe_paie', 'PAI', 4);
    }

    public function employe(): string
    {
        $last = \App\Domain\Personnel\Models\Employe::max('id') ?? 0;
        return 'EMP-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function equipement(): string
    {
        $last = \App\Domain\ParcMateriel\Models\Equipement::max('id') ?? 0;
        return 'EN-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
    }

    public function materiau(): string
    {
        $last = \App\Domain\Approvisionnement\Models\Materiau::max('id') ?? 0;
        return 'MAT-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    private function generer(string $cleParametre, string $defaut, int $padding): string
    {
        $prefix = $this->params->get($cleParametre, $defaut);
        $annee = now()->year;

        $compteur = \App\Domain\Socle\Models\Parametre::where('cle', "compteur.{$defaut}")->value('valeur') ?? 0;
        $compteur++;

        \App\Domain\Socle\Models\Parametre::updateOrCreate(
            ['cle' => "compteur.{$defaut}"],
            ['valeur' => $compteur, 'type' => 'integer']
        );

        return sprintf('%s-%s-%s', $prefix, $annee, str_pad($compteur, $padding, '0', STR_PAD_LEFT));
    }
}