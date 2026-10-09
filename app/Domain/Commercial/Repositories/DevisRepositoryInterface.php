<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Devis;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DevisRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecClient(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function avecLignes(Devis $devis): Devis;
    public function acceptes(): Collection;
    public function enAttente(): Collection;
    public function parClient(int $clientId): Collection;
    public function statistiques(): array;
    public function genererNumero(): string;
    public function margeMoyenne(): float;
}