<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleveCarburant extends Model
{
    use HasFactory;

    protected $table = 'releve_carburants';
    protected $guarded = ['id'];
    protected $casts = [
        'date_releve'           => 'date',
        'quantite'              => 'decimal:2',
        'prix_unitaire'         => 'decimal:2',
        'cout_total'            => 'decimal:2',
        'compteur'              => 'decimal:2',
        'consommation_horaire'  => 'decimal:2',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }

    public function getCoutParHeureAttribute(): ?float
    {
        return $this->consommation_horaire && $this->prix_unitaire
            ? round((float) $this->consommation_horaire * (float) $this->prix_unitaire, 2)
            : null;
    }

    // ==================== METHODS ====================
    public function calculerConsommationHoraire(?float $compteurPrecedent): void
    {
        if ($this->compteur && $compteurPrecedent !== null && $this->compteur > $compteurPrecedent) {
            $delta = (float) $this->compteur - $compteurPrecedent;
            $this->consommation_horaire = $delta > 0
                ? round((float) $this->quantite / $delta, 2)
                : 0;
        }
    }

    public function scopePeriode($q, $debut, $fin)
    {
        return $q->whereBetween('date_releve', [$debut, $fin]);
    }
}