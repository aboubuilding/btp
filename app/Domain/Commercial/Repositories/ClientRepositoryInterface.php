<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClientRepositoryInterface extends \App\Domain\Socle\Repositories\BaseRepositoryInterface
{
    public function paginateAvecCompteurs(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function findByNom(string $nom): ?Client;
    public function avecHistorique(Client $client): Client;
    public function statistiques(): array;
    public function clientsActifs(): Collection;
    public function topClients(int $limite = 10): Collection;
}