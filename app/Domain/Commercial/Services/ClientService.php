<?php
namespace App\Domain\Commercial\Services;

use App\Domain\Commercial\Models\Client;
use App\Domain\Commercial\Repositories\ClientRepositoryInterface;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function __construct(
        private ClientRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            $client = $this->repo->create($data);
            $this->journal->log('client.cree', $client);
            return $client;
        });
    }

    public function mettreAJour(Client $client, array $data): Client
    {
        return DB::transaction(function () use ($client, $data) {
            $client = $this->repo->update($client, $data);
            $this->journal->log('client.modifie', $client);
            return $client;
        });
    }

    public function desactiver(Client $client): bool
    {
        $ok = $this->repo->desactiver($client);
        $this->journal->log('client.desactive', $client);
        return $ok;
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecCompteurs($filtres, $parPage);
    }

    public function avecHistorique(Client $client): Client
    {
        return $this->repo->avecHistorique($client);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }

    public function topClients(int $limite = 10): Collection
    {
        return $this->repo->topClients($limite);
    }

    public function clientsActifs(): Collection
    {
        return $this->repo->clientsActifs();
    }
}