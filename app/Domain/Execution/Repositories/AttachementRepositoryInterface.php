<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Attachement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AttachementRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecLignes(Attachement $attachement): Attachement;
    public function parProjet(int $projetId): Collection;
    public function valides(int $projetId): Collection;
    public function prochainNumero(int $projetId): int;
}