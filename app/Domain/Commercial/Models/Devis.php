<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';
    protected $guarded = ['id'];
    protected $casts = [
        'date_devis'         => 'date',
        'coefficient_vente'  => 'decimal:3',
        'total_debourse_sec' => 'decimal:2',
        'montant_ht'         => 'decimal:2',
        'taux_tva'           => 'decimal:2',
        'montant_ttc'        => 'decimal:2',
        'valide_le'          => 'datetime',
        'etat'               => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function client()  { return $this->belongsTo(Client::class); }
    public function lots()    { return $this->hasMany(LotDevis::class); }
    public function lignes()  { return $this->hasMany(LigneDevis::class); }
    public function marche()  { return $this->hasOne(Marche::class); }
    public function validePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    // ==================== ACCESSORS ====================
    public function getMargePrevisionnelleAttribute(): float
    {
        return (float) $this->montant_ht - (float) $this->total_debourse_sec;
    }

    public function getTauxMargeAttribute(): float
    {
        return $this->montant_ht > 0
            ? round(($this->getMargePrevisionnelleAttribute() / $this->montant_ht) * 100, 2)
            : 0;
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'envoye'    => 'Envoyé',
            'accepte'   => 'Accepté',
            'refuse'    => 'Refusé',
            'expire'    => 'Expiré',
            default     => ucfirst($this->statut),
        };
    }

    public function getEstModifiableAttribute(): bool
    {
        return $this->statut === 'brouillon';
    }

    // ==================== METHODS ====================
    public function recalculerTotaux(): void
    {
        $this->total_debourse_sec = $this->lignes()->sum('debourse_sec_unitaire') ?? 0;
        $this->montant_ht = $this->lignes()->sum('montant') ?? 0;
        $this->montant_ttc = round($this->montant_ht * (1 + $this->taux_tva / 100), 2);
        $this->save();
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)   { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeAcceptes($q)  { return $q->where('statut', 'accepte'); }
    public function scopeEnAttente($q) { return $q->where('statut', 'envoye'); }
}