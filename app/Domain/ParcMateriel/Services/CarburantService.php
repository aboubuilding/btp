<?php
namespace App\Domain\ParcMateriel\Services;

use App\Domain\ParcMateriel\Models\{Equipement, ReleveCarburant};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class CarburantService
{
    public function __construct(private JournalService $journal) {}

    /**
     * RG-M02 : compteur croissant.
     */
    public function enregistrer(Equipement $equipement, array $data): ReleveCarburant
    {
        if (isset($data['compteur']) && (float) $data['compteur'] < (float) $equipement->compteur_heures_actuel) {
            throw new RegleGestionException('Compteur décroissant interdit (RG-M02).');
        }

        return DB::transaction(function () use ($equipement, $data) {
            $data['cout_total'] = round((float) $data['quantite'] * (float) $data['prix_unitaire'], 2);

            $releve = $equipement->relevesCarburant()->create($data);

            // Calcule la consommation horaire
            $compteurPrecedent = $equipement->relevesCarburant()
                ->where('id', '!=', $releve->id)
                ->whereNotNull('compteur')
                ->orderByDesc('date_releve')
                ->value('compteur');

            $releve->calculerConsommationHoraire($compteurPrecedent ? (float) $compteurPrecedent : null);
            $releve->save();

            // Met à jour le compteur de l'engin
            if (isset($data['compteur'])) {
                $equipement->update(['compteur_heures_actuel' => $data['compteur']]);
            }

            $this->journal->log('carburant.releve', $releve);
            return $releve;
        });
    }

    public function consommationMoyenne(Equipement $equipement, int $jours = 30): float
    {
        return round((float) $equipement->relevesCarburant()
            ->where('date_releve', '>=', now()->subDays($jours))
            ->avg('consommation_horaire'), 2);
    }

    public function coutTotal(Equipement $equipement, ?string $debut = null, ?string $fin = null): float
    {
        $q = $equipement->relevesCarburant();
        if ($debut && $fin) $q->whereBetween('date_releve', [$debut, $fin]);
        return (float) $q->sum('cout_total');
    }
}