<?php
namespace App\Notifications\Approvisionnement;

use App\Domain\Approvisionnement\Models\BonCommande;
use App\Notifications\BaseNotification;

class BonCommandeValideNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-file-signature';
    protected string $priorite = 'normal';

    public function __construct(public BonCommande $bonCommande)
    {
        $this->url = route('logistique.bons-commande.show', $bonCommande);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '✔️ Bon de commande validé : ' . $this->bonCommande->numero;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le BC %s du fournisseur « %s » a été validé pour un montant de %s.',
            $this->bonCommande->numero,
            $this->bonCommande->fournisseur?->nom ?? '—',
            $this->formatMontant((float) $this->bonCommande->montant_total)
        );
    }
}