<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\JournalActivite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface JournalRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateRecents(array $filtres = [], int $parPage = 50): LengthAwarePaginator;
    public function pourObjet(string $type, int $id): \Illuminate\Support\Collection;
    public function pourUtilisateur(int $userId, int $limite = 100): \Illuminate\Support\Collection;
    public function archiver(array $ids): int;
    public function purgerAvant(\DateTimeInterface $date): int;
}