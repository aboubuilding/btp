<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    use HasFactory;

    protected $table = 'attachements';
    protected $guarded = ['id'];
    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin'   => 'date',
        'numero'        => 'integer',
        'etat'          => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function projet()   { return $this->belongsTo(Projet::class); }
    public function etabliPar(){ return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'etabli_par'); }
    public function lignes()   { return $this->hasMany(LigneAttachement::class); }
    public function situation(){ return $this->hasOne(Situation::class); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    // ==================== ACCESSORS ====================
    public function getMontantTotalAttribute(): float
    {
        return (float) $this->lignes->sum(fn($l) =>
            (float) $l->quantite_periode * (float) ($l->ligneDevis?->prix_unitaire ?? 0)
        );
    }

    public function getEstValideAttribute(): bool
    {
        return $this->statut === 'valide';
    }

    public function getEstModifiableAttribute(): bool
    {
        return $this->statut !== 'valide';
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon'      => 'Brouillon',
            'valide'         => 'Validé',
            'contradictoire' => 'Contradictoire',
            default          => ucfirst($this->statut),
        };
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeValides($q) { return $q->where('statut', 'valide'); }
    public function scopeBrouillons($q) { return $q->where('statut', 'brouillon'); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
}