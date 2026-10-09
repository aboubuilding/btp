<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\Livraison;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface LivraisonRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecDetails(Livraison $livraison): Livraison;
    public function parBonCommande(int $bcId): Collection;
    public function parEntrepot(int $entrepotId): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
}