<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use App\Domain\Approvisionnement\Repositories\DemandeAchatRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, ReferenceService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DemandeAchatService
{
    public function __construct(
        private DemandeAchatRepositoryInterface $repo,
        private JournalService $journal,
        private ReferenceService $reference,
    ) {}

    public function creer(array $data, array $articles, ?int $userId = null): DemandeAchat
    {
        return DB::transaction(function () use ($data, $articles, $userId) {
            $data['numero']       = $data['numero'] ?? $this->reference->demandeAchat();
            $data['statut']       = 'en_attente';
            $data['demandeur_id'] = $userId ?? auth()->id();

            $demande = $this->repo->create($data);

            foreach ($articles as $article) {
                $demande->articles()->create($article);
            }

            $this->journal->log('demande_achat.creee', $demande);
            return $demande->fresh(['articles']);
        });
    }

    public function valider(DemandeAchat $demande, int $userId): DemandeAchat
    {
        if ($demande->statut !== 'en_attente') {
            throw new RegleGestionException('Demande déjà traitée.');
        }

        $demande = $this->repo->update($demande, [
            'statut'    => 'validee',
            'valide_par'=> $userId,
            'valide_le' => now(),
        ]);
        $this->journal->log('demande_achat.validee', $demande);
        return $demande;
    }

    public function rejeter(DemandeAchat $demande, int $userId): DemandeAchat
    {
        $demande = $this->repo->update($demande, [
            'statut'    => 'rejetee',
            'valide_par'=> $userId,
            'valide_le' => now(),
        ]);
        $this->journal->log('demande_achat.rejetee', $demande);
        return $demande;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecArticles(DemandeAchat $demande): DemandeAchat
    {
        return $this->repo->avecArticles($demande);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}