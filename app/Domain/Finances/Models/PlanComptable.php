<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Model;

class PlanComptable extends Model
{
    protected $table = 'plan_comptables';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function parent()   { return $this->belongsTo(self::class, 'parent_id'); }
    public function enfants()  { return $this->hasMany(self::class, 'parent_id'); }
    public function lignesEcritures() { return $this->hasMany(LigneEcritureComptable::class); }

    public function getLibelleCompletAttribute(): string
    {
        return "{$this->numero} — {$this->libelle}";
    }

    public function getSoldeDebitAttribute(): float
    {
        return (float) $this->lignesEcritures()
            ->whereHas('ecriture', fn($q) => $q->where('statut', 'validee'))
            ->sum('debit');
    }

    public function getSoldeCreditAttribute(): float
    {
        return (float) $this->lignesEcritures()
            ->whereHas('ecriture', fn($q) => $q->where('statut', 'validee'))
            ->sum('credit');
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeClasse($q, $classe) { return $q->where('classe', $classe); }
}