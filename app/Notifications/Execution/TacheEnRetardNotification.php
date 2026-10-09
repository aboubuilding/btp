<?php
namespace App\Notifications\Execution;

use App\Domain\Execution\Models\Projet;
use App\Notifications\BaseNotification;
use Illuminate\Support\Collection;

class TacheEnRetardNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-clock';
    protected string $priorite = 'high';

    public function __construct(
        public Projet $projet,
        public Collection $taches,
    ) {
        $this->url = route('projets.planning.gantt', $projet);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return sprintf('⏰ %d tâche(s) en retard', $this->taches->count());
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le chantier « %s » a %d tâche(s) en retard sur son planning. '
            . 'Vérifiez le diagramme de Gantt.',
            $this->projet->nom,
            $this->taches->count()
        );
    }

    public function toMail(object $notifiable)
    {
        $top = $this->taches->take(5)
            ->map(fn($t) => "• {$t->nom} — échéance {$this->formatDate($t->date_fin)}")
            ->implode("\n");

        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage() . "\n\nTâches concernées :\n" . $top,
            $this->url,
            $this->couleur,
            'Voir le planning',
            ['Chantier' => $this->projet->code . ' — ' . $this->projet->nom]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}