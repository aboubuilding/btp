<?php
namespace App\Domain\SousTraitance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'facture_sous_traitants';
    protected $guarded = ['id'];
    protected $casts = [
        'date_facture'      => 'date',
        'montant_ht'        => 'decimal:2',
        'tva'               => 'decimal:2',
        'montant_ttc'       => 'decimal:2',
        'retenue_garantie'  => 'decimal:2',
    ];

    public function contrat()   { return $this->belongsTo(ContratSousTraitant::class, 'contrat_sous_traitant_id'); }
    public function paiements() { return $this->hasMany(PaiementSousTraitant::class, 'facture_sous_traitant_id'); }

    public function getTotalPayeAttribute(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    public function getResteAPayerAttribute(): float
    {
        return max(0, (float) $this->montant_ttc - $this->total_paye);
    }

    public function getNetAPayerAttribute(): float
    {
        return (float) $this->montant_ttc - (float) $this->retenue_garantie;
    }

    public function getEstPayeeAttribute(): bool
    {
        return $this->total_paye >= $this->montant_ttc;
    }

    public function scopeRecues($q) { return $q->where('statut', 'recue'); }
    public function scopePayees($q) { return $q->where('statut', 'payee'); }
}