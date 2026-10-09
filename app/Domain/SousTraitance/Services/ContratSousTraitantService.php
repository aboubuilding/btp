<?php
namespace App\Domain\SousTraitance\Services;

use App\Domain\SousTraitance\Models\{ContratSousTraitant, Soustraitant};
use App\Domain\SousTraitance\Repositories\ContratSousTraitantRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContratSousTraitantService
{
    public function __construct(
        private ContratSousTraitantRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    /**
     * RG-T02 : pas de contrat avec un blacklisté.
     */
    public function creer(array $data): ContratSousTraitant
    {
        $soustraitant = Soustraitant::findOrFail($data['sous_traitant_id']);

        if ($soustraitant->est_blackliste) {
            throw new RegleGestionException('Sous-traitant blacklisté (RG-T02).');
        }

        return DB::transaction(function () use ($data) {
            $contrat = $this->repo->create($data);
            $this->journal->log('contrat_st.cree', $contrat);
            return $contrat;
        });
    }

    public function mettreAJour(ContratSousTraitant $contrat, array $data): ContratSousTraitant
    {
        $contrat = $this->repo->update($contrat, $data);
        $this->journal->log('contrat_st.modifie', $contrat);
        return $contrat;
    }

    public function resilier(ContratSousTraitant $contrat): ContratSousTraitant
    {
        $contrat = $this->repo->update($contrat, ['statut' => 'resilie']);
        $this->journal->log('contrat_st.resilie', $contrat);
        return $contrat;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(ContratSousTraitant $contrat): ContratSousTraitant
    {
        return $this->repo->avecDetails($contrat);
    }

    public function enCours(): Collection
    {
        return $this->repo->enCours();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}