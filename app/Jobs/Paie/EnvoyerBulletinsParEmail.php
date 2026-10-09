<?php
namespace App\Jobs\Paie;

use App\Domain\Personnel\Models\BulletinPaie;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class EnvoyerBulletinsParEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 3;
    public string $queue = 'communications';

    public function __construct(public int $periodeId) {}

    public function handle(CommunicationService $communication): void
    {
        $bulletins = BulletinPaie::with(['employe.user', 'periode'])
            ->where('periode_paie_id', $this->periodeId)
            ->where('statut', 'valide')
            ->get();

        $envoyes = 0;

        foreach ($bulletins as $bulletin) {
            $user = $bulletin->employe?->user;
            if (!$user?->email) continue;

            try {
                $communication->envoyerDepuisTemplate(
                    'bulletin.envoi',
                    [
                        'employe_nom' => $bulletin->employe->nom_complet,
                        'periode'     => $bulletin->periode->libelle,
                        'net_a_payer' => number_format($bulletin->net_a_payer, 0, ',', ' ') . ' FCFA',
                    ],
                    [['email' => $user->email, 'nom' => $user->nom]],
                    $bulletin
                );
                $envoyes++;
            } catch (\Throwable $e) {
                Log::error('[Job] Envoi bulletin échoué', [
                    'bulletin_id' => $bulletin->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        Log::info('[Job] Envoi bulletins', ['envoyes' => $envoyes, 'total' => $bulletins->count()]);
    }
}