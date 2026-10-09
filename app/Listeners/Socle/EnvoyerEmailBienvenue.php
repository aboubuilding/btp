<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\UtilisateurCree;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerEmailBienvenue implements ShouldQueue
{
    public int $tries = 3;
    public string $queue = 'communications';

    public function __construct(private CommunicationService $communication) {}

    public function handle(UtilisateurCree $event): void
    {
        try {
            $user = $event->user;

            $this->communication->envoyerDepuisTemplate(
                'utilisateur.bienvenue',
                [
                    'utilisateur_nom'  => $user->nom,
                    'utilisateur_email'=> $user->email,
                    'role_nom'         => $user->role?->nom ?? 'Utilisateur',
                ],
                [['email' => $user->email, 'nom' => $user->nom]]
            );

            Log::info('[Listener] Email de bienvenue envoyé', ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            Log::error('[Listener] Échec email de bienvenue', [
                'user_id' => $event->user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}