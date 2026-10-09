<?php
namespace App\Domain\SousTraitance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'contrat_sous_traitants';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'             => 'date',
        'date_fin'               => 'date',
        'montant'                => 'decimal:2',
        'taux_retenue_garantie'  => 'decimal:2',
        'montant_avenants'       => 'decimal:2',
        'etat'                   => 'integer',
    ];

    public function soustraitant() { return $this->belongsTo(Soustraitant::class, 'sous_traitant_id'); }
    public function projet()       { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function factures()     { return $this->hasMany(FactureSousTraitant::class, 'contrat_sous_traitant_id'); }

    public function paiements()
    {
        return $this->hasManyThrough(
            PaiementSousTraitant::class,
            FactureSousTraitant::class,
            'contrat_sous_traitant_id',
            'facture_sous_traitant_id'
        );
    }

    // ==================== ACCESSORS ====================
    public function getMontantActualiseAttribute(): float
    {
        return (float) $this->montant + (float) $this->montant_avenants;
    }

    public function getTotalPayeAttribute(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    public function getPlafondPaiementAttribute(): float
    {
        return $this->getMontantActualiseAttribute() * (1 - $this->taux_retenue_garantie / 100);
    }

    public function getResteAPayerAttribute(): float
    {
        return max(0, $this->plafond_paiement - $this->total_paye);
    }

    public function getTauxAvancementAttribute(): float
    {
        return $this->plafond_paiement > 0
            ? round(($this->total_paye / $this->plafond_paiement) * 100, 2)
            : 0;
    }

    public function getEstEnCoursAttribute(): bool { return $this->statut === 'en_cours'; }
    public function getEstTermineAttribute(): bool  { return $this->statut === 'termine'; }
    public function getEstResilieAttribute(): bool  { return $this->statut === 'resilie'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'en_cours' => 'En cours',
            'termine'  => 'Terminé',
            'resilie'  => 'Résilié',
            default    => ucfirst($this->statut),
        };
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeEnCours($q) { return $q->where('statut', 'en_cours'); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
}