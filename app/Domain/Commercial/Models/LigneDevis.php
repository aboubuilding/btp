<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Model;

class LigneDevis extends Model
{
    protected $table = 'ligne_devis';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite'              => 'decimal:3',
        'debourse_sec_unitaire' => 'decimal:2',
        'prix_unitaire'         => 'decimal:2',
        'montant'               => 'decimal:2',
        'ordre'                 => 'integer',
        'etat'                  => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function devis() { return $this->belongsTo(Devis::class); }
    public function lot()   { return $this->belongsTo(LotDevis::class, 'lot_devis_id'); }

    public function taches()
    {
        return $this->hasMany(\App\Domain\Execution\Models\Tache::class, 'ligne_devis_id');
    }

    public function ligneAttachements()
    {
        return $this->hasMany(\App\Domain\Execution\Models\LigneAttachement::class, 'ligne_devis_id');
    }

    // ==================== HELPERS ====================
    public function getMargeUnitaireAttribute(): float
    {
        return (float) $this->prix_unitaire - (float) $this->debourse_sec_unitaire;
    }

    public function getQuantiteFactureeAttribute(): float
    {
        return (float) $this->ligneAttachements()
            ->whereHas('attachement', fn($q) => $q->where('statut', 'valide'))
            ->sum('quantite_cumulee');
    }

    public function getResteAExecuterAttribute(): float
    {
        return max(0, (float) $this->quantite - $this->getQuantiteFactureeAttribute());
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
}