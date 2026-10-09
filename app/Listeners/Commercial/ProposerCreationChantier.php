<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\MarcheSigne;
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProposerCreationChantier implements ShouldQueue
{
    public string $queue = 'alertes';

    public function __construct(private JournalService $journal) {}

    public function handle(MarcheSigne $event): void
    {
        $marche = $event->marche;

        // Notifie le DT pour créer le chantier
        $destinataires = User::whereHas('role', fn($q) =>
            $q->whereIn('slug', ['directeur_technique'])
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, new \App\Notifications\Commercial\CreationChantierProposeeNotification($marche));
        }

        $this->journal->log('marche.proposer_creation_chantier', $marche);

        Log::info('[Listener] Proposition de création de chantier', [
            'marche_id' => $marche->id,
        ]);
    }
}