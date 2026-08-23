<?php

namespace App\Repositories\Eloquent;

use App\Models\ContratSousTraitant;
use App\Repositories\Interfaces\ContratSousTraitantRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContratSousTraitantRepository extends BaseRepository implements ContratSousTraitantRepositoryInterface
{
    public function model(): string
    {
        return ContratSousTraitant::class;
    }

    public function getContratsWithRelations(): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getContratsBySousTraitant(int $sousTraitantId): array
    {
        return $this->activeQuery()
            ->with(['projet'])
            ->where('sous_traitant_id', $sousTraitantId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getContratsByProjet(int $projetId): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant'])
            ->where('projet_id', $projetId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getContratsEnCours(): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet'])
            ->where('statut', 'en_cours')
            ->where('date_fin', '>=', Carbon::now())
            ->orderBy('date_fin', 'asc')
            ->get()
            ->toArray();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $contrat = $this->find($id);
        if (!$contrat) {
            return false;
        }
        $contrat->statut = $status;
        return $contrat->save();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet'])
            ->where(function ($query) use ($keyword) {
                $query->where('numero_contrat', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('sousTraitant', function ($q) use ($keyword) {
                        $q->where('nom_entreprise', 'LIKE', "%{$keyword}%")
                            ->orWhere('personne_contact', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('projet', function ($q) use ($keyword) {
                        $q->where('nom', 'LIKE', "%{$keyword}%")
                            ->orWhere('code', 'LIKE', "%{$keyword}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->activeQuery()->count();
        $enCours = $this->activeQuery()->where('statut', 'en_cours')->count();
        $termines = $this->activeQuery()->where('statut', 'termine')->count();
        $resilies = $this->activeQuery()->where('statut', 'resilie')->count();

        $montantTotal = $this->activeQuery()->sum('montant');

        return [
            'total' => $total,
            'en_cours' => $enCours,
            'termines' => $termines,
            'resilies' => $resilies,
            'montant_total' => $montantTotal,
        ];
    }

    public function generateNumeroContrat(): string
    {
        $year = date('Y');
        $last = $this->activeQuery()
            ->where('numero_contrat', 'LIKE', "CT-ST-{$year}-%")
            ->orderBy('numero_contrat', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->numero_contrat, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "CT-ST-{$year}-{$newNumber}";
    }
}
