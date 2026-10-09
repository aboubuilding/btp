<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marche extends Model
{
    use HasFactory;

    protected $table = 'marches';
    protected $guarded = ['id'];
    protected $casts = [
        'date_signature'           => 'date',
        'date_ordre_service'       => 'date',
        'montant_initial'          => 'decimal:2',
        'taux_avance'              => 'decimal:2',
        'taux_retenue_garantie'    => 'decimal:2',
        'etat'                     => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function client()   { return $this->belongsTo(Client::class); }
    public function devis()    { return $this->belongsTo(Devis::class); }
    public function avenants() { return $this->hasMany(AvenantMarche::class); }
    public function cautions() { return $this->hasMany(CautionMarche::class); }
    public function projets()  { return $this->hasMany(\App\Domain\Execution\Models\Projet::class); }

    public function avenantsSignes()
    {
        return $this->avenants()->where('est_signe', true);
    }

    // ==================== ACCESSORS ====================
    public function getMontantActualiseAttribute(): float
    {
        return (float) $this->montant_initial
             + (float) $this->avenantsSignes()->sum('montant');
    }

    public function getAvanceInitialeAttribute(): float
    {
        return (float) $this->montant_initial * ($this->taux_avance / 100);
    }

    public function getSoldeAvanceRestantAttribute(): float
    {
        $dejaRembourse = (float) \App\Domain\Execution\Models\Situation::whereIn(
            'projet_id', $this->projets()->pluck('id')
        )->whereIn('statut', ['validee', 'transmise', 'approuvee', 'facturee'])
         ->sum('remboursement_avance');

        return max(0, $this->getAvanceInitialeAttribute() - $dejaRembourse);
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon'   => 'Brouillon',
            'signe'       => 'Signé',
            'en_cours'    => 'En cours',
            'receptionne' => 'Réceptionné',
            'clos'        => 'Clos',
            default       => ucfirst($this->statut),
        };
    }

    public function getEstSigneAttribute(): bool
    {
        return in_array($this->statut, ['signe', 'en_cours', 'receptionne', 'clos'], true);
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)   { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeSignes($q)  { return $q->whereIn('statut', ['signe', 'en_cours', 'receptionne']); }
}