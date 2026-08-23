<?php

namespace App\Repositories\Eloquent;

use App\Models\Soustraitant;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SoustraitantRepository extends BaseRepository implements SoustraitantRepositoryInterface
{
    public function model(): string
    {
        return Soustraitant::class;
    }

    public function getActiveSoustraitants(): array
    {
        return $this->activeQuery()
            ->where('statut', 'actif')
            ->orderBy('nom_entreprise')
            ->get()
            ->toArray();
    }

    public function getSoustraitantsBySpecialite(string $specialite): array
    {
        return $this->activeQuery()
            ->where('specialite', $specialite)
            ->where('statut', 'actif')
            ->orderBy('nom_entreprise')
            ->get()
            ->toArray();
    }

    public function getTopRated(int $limit = 5): array
    {
        return $this->activeQuery()
            ->where('statut', 'actif')
            ->whereNotNull('note')
            ->orderBy('note', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $soustraitant = $this->withSupprime()->find($id);
        if (!$soustraitant) {
            return false;
        }
        $soustraitant->statut = $status;
        return $soustraitant->save();
    }

    public function search(string $keyword): array
    {
        return $this->withSupprime()
            ->where(function ($query) use ($keyword) {
                $query->where('nom_entreprise', 'LIKE', "%{$keyword}%")
                    ->orWhere('personne_contact', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('telephone', 'LIKE', "%{$keyword}%")
                    ->orWhere('adresse', 'LIKE', "%{$keyword}%")
                    ->orWhere('specialite', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('nom_entreprise')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->withSupprime()->count();
        $actifs = $this->activeQuery()->where('statut', 'actif')->count();
        $suspendus = $this->activeQuery()->where('statut', 'suspendu')->count();
        $blacklistes = $this->activeQuery()->where('statut', 'blackliste')->count();
        $supprimes = $this->onlySupprime()->count();

        return [
            'total' => $total,
            'actifs' => $actifs,
            'suspendus' => $suspendus,
            'blacklistes' => $blacklistes,
            'supprimes' => $supprimes,
        ];
    }
}
