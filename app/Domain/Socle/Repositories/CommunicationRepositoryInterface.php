<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Communication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CommunicationRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function aEnvoyer(): Collection;
    public function brouillons(): Collection;
    public function echoues(): Collection;
    public function statistiques(): array;
    public function pourObjet(string $type, int $id): Collection;
    public function marquerEnvoye(Communication $comm, ?string $erreur = null): bool;
}