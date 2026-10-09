<?php
namespace App\Notifications\Approvisionnement;

use App\Domain\Approvisionnement\Models\{Materiau, NiveauStock};
use App\Notifications\BaseNotification;

class StockSousSeuilNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-triangle-exclamation';
    protected string $priorite = 'high';

    public function __construct(
        public Materiau $materiau,
        public NiveauStock $niveau,
    ) {
        $this->url = route('logistique.stocks.index', ['entrepot_id' => $niveau->entrepot_id]);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Stock sous le seuil d\'alerte';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le matériau « %s » est sous le seuil dans le dépôt « %s » (Qté : %s %s / Seuil : %s).',
            $this->materiau->nom,
            $this->niveau->entrepot?->nom ?? '—',
            number_format((float) $this->niveau->quantite, 2, ',', ' '),
            $this->materiau->unite,
            number_format((float) $this->materiau->seuil_alerte_stock_min, 2, ',', ' ')
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Commander',
            [
                'Matériau'    => $this->materiau->nom . ' (' . $this->materiau->code . ')',
                'Dépôt'       => $this->niveau->entrepot?->nom,
                'Quantité'    => number_format((float) $this->niveau->quantite, 2, ',', ' ') . ' ' . $this->materiau->unite,
                'Seuil alerte'=> number_format((float) $this->materiau->seuil_alerte_stock_min, 2, ',', ' ') . ' ' . $this->materiau->unite,
                'À commander' => number_format((float) $this->materiau->seuil_alerte_stock_min * 2, 2, ',', ' ') . ' ' . $this->materiau->unite,
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}