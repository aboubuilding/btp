<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\BonCommande;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BonCommandeRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(BonCommande $bc): BonCommande;
    public function enCours(): Collection;
    public function parFournisseur(int $fournisseurId): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
    public function recalculerTotal(BonCommande $bc): void;
}