<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    use HasFactory;

    protected $table = 'situations';
    protected $guarded = ['id'];
    protected $casts = [
        'periode_debut'        => 'date',
        'periode_fin'          => 'date',
        'numero'               => 'integer',
        'montant_cumule_ht'    => 'decimal:2',
        'montant_precedent_ht' => 'decimal:2',
        'montant_periode_ht'   => 'decimal:2',
        'remboursement_avance' => 'decimal:2',
        'retenue_garantie'     => 'decimal:2',
        'penalites'            => 'decimal:2',
        'tva'                  => 'decimal:2',
        'net_a_payer'          => 'decimal:2',
        'valide_le'            => 'datetime',
        'etat'                 => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function projet()      { return $this->belongsTo(Projet::class); }
    public function attachement() { return $this->belongsTo(Attachement::class); }
    public function validePar()   { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function facture()
    {
        return $this->hasOne(\App\Domain\Finances\Models\Facture::class, 'situation_id');
    }

    // ==================== ACCESSORS ====================
    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon'  => 'Brouillon',
            'validee'    => 'Validée',
            'transmise'  => 'Transmise',
            'approuvee'  => 'Approuvée',
            'facturee'   => 'Facturée',
            'rejetee'    => 'Rejetée',
            default      => ucfirst($this->statut),
        };
    }

    public function getEstModifiableAttribute(): bool
    {
        return $this->statut === 'brouillon';
    }

    public function getEstFactureeAttribute(): bool
    {
        return $this->statut === 'facturee' && $this->facture()->exists();
    }

    public function getMontantTtcAttribute(): float
    {
        return (float) $this->montant_periode_ht + (float) $this->tva;
    }

    // ==================== METHODS ====================
    public function calculerMontants(float $tauxRg, float $tauxTva, float $soldeAvance): void
    {
        $this->montant_periode_ht = (float) $this->montant_cumule_ht - (float) $this->montant_precedent_ht;
        $this->retenue_garantie = round($this->montant_periode_ht * $tauxRg / 100, 2);
        $this->remboursement_avance = min($this->montant_periode_ht * 0.20, $soldeAvance);
        $this->tva = round($this->montant_periode_ht * $tauxTva / 100, 2);
        $this->net_a_payer = $this->montant_periode_ht
                           + $this->tva
                           - $this->retenue_garantie
                           - $this->remboursement_avance
                           - $this->penalites;
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeApprouvees($q) { return $q->where('statut', 'approuvee'); }
    public function scopeFacturees($q) { return $q->where('statut', 'facturee'); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
}