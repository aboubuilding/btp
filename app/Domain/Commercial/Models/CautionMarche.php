<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Model;

class CautionMarche extends Model
{
    protected $table = 'caution_marches';
    protected $guarded = ['id'];
    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'montant'       => 'decimal:2',
        'etat'          => 'integer',
    ];

    public function marche() { return $this->belongsTo(Marche::class); }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'soumission'       => 'Caution de soumission',
            'avance'           => 'Caution d\'avance',
            'bonne_execution'  => 'Caution de bonne exécution',
            default            => ucfirst($this->type),
        };
    }

    public function getExpireBientotAttribute(): bool
    {
        return $this->date_echeance
            && $this->statut === 'active'
            && now()->diffInDays($this->date_echeance, false) <= 30
            && $this->date_echeance->isFuture();
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->date_echeance?->isPast() ?? false;
    }

    public function scopeActives($q) { return $q->where('statut', 'active'); }
    public function scopeActif($q)   { return $q->where('etat', 1); }
}