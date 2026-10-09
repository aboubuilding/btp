<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Model;

class LigneAttachement extends Model
{
    protected $table = 'ligne_attachements';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite_periode'  => 'decimal:3',
        'quantite_cumulee'  => 'decimal:3',
        'etat'              => 'integer',
    ];

    public function attachement()  { return $this->belongsTo(Attachement::class); }
    public function ligneDevis()   { return $this->belongsTo(\App\Domain\Commercial\Models\LigneDevis::class, 'ligne_devis_id'); }

    public function getMontantPeriodeAttribute(): float
    {
        return (float) $this->quantite_periode * (float) ($this->ligneDevis?->prix_unitaire ?? 0);
    }

    public function getMontantCumuleAttribute(): float
    {
        return (float) $this->quantite_cumulee * (float) ($this->ligneDevis?->prix_unitaire ?? 0);
    }

    public function getQuantiteMarcheAttribute(): float
    {
        return (float) ($this->ligneDevis?->quantite ?? 0);
    }

    public function getDepassementAttribute(): float
    {
        return max(0, (float) $this->quantite_cumulee - $this->getQuantiteMarcheAttribute());
    }
}