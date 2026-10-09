<?php
namespace App\Notifications\Commercial;

use App\Domain\Commercial\Models\Marche;
use App\Notifications\BaseNotification;

class MarcheSigneNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-file-signature';
    protected string $priorite = 'high';

    public function __construct(public Marche $marche)
    {
        $this->url = route('commercial.marches.show', $marche);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🎉 Marché signé : ' . $this->marche->reference;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Marché « %s » signé avec « %s » — Montant initial : %s. '
            . 'Vous pouvez maintenant créer le chantier.',
            $this->marche->objet,
            $this->marche->client?->nom ?? '—',
            $this->formatMontant((float) $this->marche->montant_initial)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Créer le chantier',
            [
                'Référence'       => $this->marche->reference,
                'Client'          => $this->marche->client?->nom,
                'Montant initial' => $this->formatMontant((float) $this->marche->montant_initial),
                'Délai'           => $this->marche->delai_contractuel_jours . ' jours',
                'Taux avance'     => $this->marche->taux_avance . ' %',
                'Retenue'         => $this->marche->taux_retenue_garantie . ' %',
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}