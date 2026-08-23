<?php

namespace App\Repositories\Eloquent;

use App\Models\EvaluationSousTraitant;
use App\Repositories\Interfaces\EvaluationSousTraitantRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EvaluationSousTraitantRepository extends BaseRepository implements EvaluationSousTraitantRepositoryInterface
{
    public function model(): string
    {
        return EvaluationSousTraitant::class;
    }

    public function getEvaluationsWithRelations(): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet', 'evaluateur'])
            ->orderBy('date_evaluation', 'desc')
            ->get()
            ->toArray();
    }

    public function getEvaluationsBySoustraitant(int $soustraitantId): array
    {
        return $this->activeQuery()
            ->with(['projet', 'evaluateur'])
            ->where('sous_traitant_id', $soustraitantId)
            ->orderBy('date_evaluation', 'desc')
            ->get()
            ->toArray();
    }

    public function getEvaluationsByProjet(int $projetId): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'evaluateur'])
            ->where('projet_id', $projetId)
            ->orderBy('date_evaluation', 'desc')
            ->get()
            ->toArray();
    }

    public function getLastEvaluations(int $limit = 10): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet', 'evaluateur'])
            ->orderBy('date_evaluation', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->with(['sousTraitant', 'projet', 'evaluateur'])
            ->where(function ($query) use ($keyword) {
                $query->where('commentaires', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('sousTraitant', function ($q) use ($keyword) {
                        $q->where('nom_entreprise', 'LIKE', "%{$keyword}%")
                            ->orWhere('personne_contact', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('projet', function ($q) use ($keyword) {
                        $q->where('nom', 'LIKE', "%{$keyword}%")
                            ->orWhere('code', 'LIKE', "%{$keyword}%");
                    });
            })
            ->orderBy('date_evaluation', 'desc')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->activeQuery()->count();

        $moyenneGlobale = $this->activeQuery()
            ->select(
                DB::raw('AVG(note_qualite) as avg_qualite'),
                DB::raw('AVG(note_delai) as avg_delai'),
                DB::raw('AVG(note_securite) as avg_securite')
            )
            ->first();

        $parMois = $this->activeQuery()
            ->select(DB::raw('DATE_FORMAT(date_evaluation, "%Y-%m") as mois'), DB::raw('count(*) as total'))
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get()
            ->toArray();

        $parNote = $this->activeQuery()
            ->select(
                DB::raw('AVG(note_qualite) as qualite'),
                DB::raw('AVG(note_delai) as delai'),
                DB::raw('AVG(note_securite) as securite')
            )
            ->first();

        return [
            'total' => $total,
            'moyenne_qualite' => round($moyenneGlobale->avg_qualite ?? 0, 1),
            'moyenne_delai' => round($moyenneGlobale->avg_delai ?? 0, 1),
            'moyenne_securite' => round($moyenneGlobale->avg_securite ?? 0, 1),
            'moyenne_generale' => round(
                (($moyenneGlobale->avg_qualite ?? 0) +
                    ($moyenneGlobale->avg_delai ?? 0) +
                    ($moyenneGlobale->avg_securite ?? 0)) / 3, 1
            ),
            'par_mois' => $parMois,
            'par_note' => [
                'qualite' => round($parNote->qualite ?? 0, 1),
                'delai' => round($parNote->delai ?? 0, 1),
                'securite' => round($parNote->securite ?? 0, 1),
            ]
        ];
    }

    public function getMoyennesBySoustraitant(int $soustraitantId): array
    {
        $stats = $this->activeQuery()
            ->where('sous_traitant_id', $soustraitantId)
            ->select(
                DB::raw('AVG(note_qualite) as avg_qualite'),
                DB::raw('AVG(note_delai) as avg_delai'),
                DB::raw('AVG(note_securite) as avg_securite'),
                DB::raw('COUNT(*) as total')
            )
            ->first();

        return [
            'qualite' => round($stats->avg_qualite ?? 0, 1),
            'delai' => round($stats->avg_delai ?? 0, 1),
            'securite' => round($stats->avg_securite ?? 0, 1),
            'moyenne' => round((($stats->avg_qualite ?? 0) +
                    ($stats->avg_delai ?? 0) +
                    ($stats->avg_securite ?? 0)) / 3, 1),
            'total' => $stats->total ?? 0,
        ];
    }
}
