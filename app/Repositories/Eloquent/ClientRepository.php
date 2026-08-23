<?php

namespace App\Repositories\Eloquent;

use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    public function model(): string
    {
        return Client::class;
    }

    public function getClientsWithProjects(): array
    {
        return $this->activeQuery()
            ->withCount(['projets' => function ($query) {
                $query->where('etat', 1);
            }])
            ->with(['projets' => function ($query) {
                $query->where('etat', 1)->select('id', 'client_id', 'statut', 'nom');
            }])
            ->orderBy('nom')
            ->get()
            ->toArray();
    }

    public function getActiveClients(): array
    {
        return $this->activeQuery()
            ->orderBy('nom')
            ->get()
            ->toArray();
    }

    public function getTopClients(int $limit = 5): array
    {
        return $this->activeQuery()
            ->withCount(['projets' => function ($query) {
                $query->where('etat', 1);
            }])
            ->orderBy('projets_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function toggleActive(int $id): bool
    {
        $client = $this->find($id);
        if (!$client) {
            return false;
        }
        $client->etat = $client->etat === 1 ? 2 : 1;
        return $client->save();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->withCount(['projets' => function ($query) {
                $query->where('etat', 1);
            }])
            ->where(function ($query) use ($keyword) {
                $query->where('nom', 'LIKE', "%{$keyword}%")
                    ->orWhere('personne_contact', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('telephone', 'LIKE', "%{$keyword}%")
                    ->orWhere('adresse', 'LIKE', "%{$keyword}%")
                    ->orWhere('nif', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('nom')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->count();
        $actifs = $this->activeQuery()->count();
        $inactifs = $this->onlySupprime()->count();

        $types = $this->activeQuery()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->toArray();

        return [
            'total' => $total,
            'actifs' => $actifs,
            'inactifs' => $inactifs,
            'types' => $types,
        ];
    }
}
