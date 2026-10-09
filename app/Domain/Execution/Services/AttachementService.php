<?php
namespace App\Domain\Execution\Services;

use App\Domain\Execution\Models\{Attachement, Projet};
use App\Domain\Execution\Repositories\AttachementRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AttachementService
{
    public function __construct(
        private AttachementRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(Projet $projet, array $data, array $lignes = [], ?int $userId = null): Attachement
    {
        return DB::transaction(function () use ($projet, $data, $lignes, $userId) {
            $data['projet_id'] = $projet->id;
            $data['numero']    = $this->repo->prochainNumero($projet->id);
            $data['etabli_par'] = $userId ?? auth()->id();
            $data['statut']    = 'brouillon';

            $attachement = $this->repo->create($data);

            foreach ($lignes as $ligne) {
                $attachement->lignes()->create($ligne);
            }

            $this->journal->log('attachement.cree', $attachement);
            return $attachement->fresh(['lignes.ligneDevis']);
        });
    }

    public function mettreAJour(Attachement $attachement, array $data, array $lignes = []): Attachement
    {
        if (!$attachement->est_modifiable) {
            throw new RegleGestionException('Attachement validé non modifiable.');
        }

        return DB::transaction(function () use ($attachement, $data, $lignes) {
            $attachement->update($data);

            if (!empty($lignes)) {
                $attachement->lignes()->delete();
                foreach ($lignes as $ligne) {
                    $attachement->lignes()->create($ligne);
                }
            }

            $this->journal->log('attachement.modifie', $attachement);
            return $attachement->fresh(['lignes']);
        });
    }

    public function valider(Attachement $attachement): Attachement
    {
        if ($attachement->statut === 'valide') {
            throw new RegleGestionException('Attachement déjà validé.');
        }

        $attachement = $this->repo->update($attachement, ['statut' => 'valide']);
        $this->journal->log('attachement.valide', $attachement);
        return $attachement;
    }

    public function supprimer(Attachement $attachement): bool
    {
        if ($attachement->statut === 'valide') {
            throw new RegleGestionException('Impossible de supprimer un attachement validé.');
        }
        return $this->repo->desactiver($attachement);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecLignes(Attachement $attachement): Attachement
    {
        return $this->repo->avecLignes($attachement);
    }
}