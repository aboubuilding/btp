<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvement_stocks';
    protected $guarded = ['id'];
    protected $casts = [
        'date_mouvement' => 'date',
        'quantite'       => 'decimal:3',
        'prix_unitaire'  => 'decimal:2',
    ];

    // ==================== RELATIONS ====================
    public function entrepot() { return $this->belongsTo(Entrepot::class); }
    public function materiau() { return $this->belongsTo(Materiau::class); }
    public function projet()   { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function enregistrePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'enregistre_par'); }

    public function reference(): MorphTo
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    // ==================== ACCESSORS ====================
    public function getEstEntreeAttribute(): bool
    {
        return in_array($this->type, ['entree', 'transfert_entree'], true);
    }

    public function getEstSortieAttribute(): bool
    {
        return in_array($this->type, ['sortie', 'transfert_sortie'], true);
    }

    public function getCoutTotalAttribute(): float
    {
        return round((float) $this->quantite * (float) $this->prix_unitaire, 2);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'entree'           => 'Entrée',
            'sortie'           => 'Sortie',
            'transfert_entree' => 'Transfert (entrée)',
            'transfert_sortie' => 'Transfert (sortie)',
            'ajustement'       => 'Ajustement',
            default            => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    // ==================== SCOPES ====================
    public function scopeEntrees($q) { return $q->whereIn('type', ['entree', 'transfert_entree']); }
    public function scopeSorties($q) { return $q->whereIn('type', ['sortie', 'transfert_sortie']); }
    public function scopeType($q, $type) { return $q->where('type', $type); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopePeriode($q, $debut, $fin)
    {
        return $q->whereBetween('date_mouvement', [$debut, $fin]);
    }
}