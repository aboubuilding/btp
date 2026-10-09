<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** Couleur de l'icône : info | success | warning | danger */
    protected string $couleur = 'info';

    /** Icône FontAwesome */
    protected string $icone = 'fa-bell';

    /** URL cible au clic */
    protected string $url = '#';

    /** Priorité : low | normal | high */
    protected string $priorite = 'normal';

    /** Canaux par défaut (surchargeable). */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** Représentation en base de données. */
    public function toArray(object $notifiable): array
    {
        return [
            'titre'     => $this->getTitre(),
            'message'   => $this->getMessage(),
            'icone'     => $this->icone,
            'couleur'   => $this->couleur,
            'url'       => $this->url,
            'priorite'  => $this->priorite,
            'date'      => now()->toIso8601String(),
            'type'      => class_basename(static::class),
        ];
    }

    /** Constructeur du mail associé. */
    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }

    // ============================================================
    // MÉTHODES ABSTRAITES À IMPLÉMENTER
    // ============================================================
    abstract protected function getTitre(): string;
    abstract protected function getMessage(): string;

    // ============================================================
    // HELPERS
    // ============================================================
    protected function formatMontant(float $montant): string
    {
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }

    protected function formatDate(?\DateTimeInterface $date): string
    {
        return $date?->format('d/m/Y') ?? '—';
    }

    protected function formatDateHeure(?\DateTimeInterface $date): string
    {
        return $date?->format('d/m/Y à H:i') ?? '—';
    }

    protected function tronquer(string $texte, int $longueur = 200): string
    {
        return Str::limit($texte, $longueur);
    }
}