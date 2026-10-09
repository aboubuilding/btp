<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DemandeAchatRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecArticles(DemandeAchat $demande): DemandeAchat;
    public function enAttente(): Collection;
    public function parProjet(int $projetId): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
}