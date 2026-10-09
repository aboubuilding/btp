<?php
namespace App\Notifications\Approvisionnement;

use App\Domain\Approvisionnement\Models\Livraison;
use App\Notifications\BaseNotification;

class LivraisonReceptionneeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-dolly';
    protected string $priorite = 'normal';

    public function __construct(public Livraison $livraison)
    {
        $this->url = route('logistique.livraisons.show', $livraison);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '📦 Livraison réceptionnée : ' . $this->livraison->numero;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Livraison du BC %s réceptionnée au dépôt « %s » le %s (%d article(s)).',
            $this->livraison->bonCommande?->numero ?? '—',
            $this->livraison->entrepot?->nom ?? '—',
            $this->formatDate($this->livraison->date_livraison),
            $this->livraison->articles->count()
        );
    }
}