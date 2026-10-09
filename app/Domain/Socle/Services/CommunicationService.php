<?php
namespace App\Domain\Socle\Services;

use App\Domain\Socle\Models\{Communication, CommunicationTemplate};
use App\Domain\Socle\Repositories\CommunicationRepositoryInterface;
use App\Mail\CommunicationMail;
use Illuminate\Support\Facades\{Log, Mail};

class CommunicationService
{
    public function __construct(
        private CommunicationRepositoryInterface $repo,
    ) {}

    public function envoyerEmail(Communication $communication): bool
    {
        try {
            $emails = $communication->emails_destinataires;
            if (empty($emails)) {
                throw new \RuntimeException('Aucun destinataire valide.');
            }

            Mail::to($emails)->send(new CommunicationMail($communication));
            $this->repo->marquerEnvoye($communication);
            return true;
        } catch (\Throwable $e) {
            Log::error('Communication error', [
                'comm_id' => $communication->id,
                'error'   => $e->getMessage(),
            ]);
            $this->repo->marquerEnvoye($communication, $e->getMessage());
            return false;
        }
    }

    public function envoyerDepuisTemplate(
        string $codeTemplate,
        array $variables,
        array $destinataires,
        mixed $communicable = null,
        ?int $expediteurId = null
    ): Communication {
        $template = CommunicationTemplate::where('code', $codeTemplate)
            ->where('etat', 1)
            ->firstOrFail();

        $rendu = $template->render($variables);

        $communication = $this->repo->create([
            'sujet'             => $rendu['sujet'],
            'corps'             => $rendu['corps'],
            'type'              => 'email',
            'statut'            => 'brouillon',
            'destinataires'     => $destinataires,
            'expediteur_id'     => $expediteurId ?? auth()->id(),
            'communicable_type' => $communicable ? get_class($communicable) : null,
            'communicable_id'   => $communicable?->id,
        ]);

        $this->envoyerEmail($communication);
        return $communication->fresh();
    }

    public function envoyerAsync(Communication $communication): void
    {
        dispatch(function () use ($communication) {
            app(self::class)->envoyerEmail($communication);
        })->onQueue('emails');
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function aEnvoyer()
    {
        return $this->repo->aEnvoyer();
    }
}