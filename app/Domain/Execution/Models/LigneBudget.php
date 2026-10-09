<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBudget extends Model
{
    use HasFactory;

    protected $table = 'ligne_budgets';
    protected $guarded = ['id'];
    protected $casts = [
        'montant_prevu' => 'decimal:2',
        'montant_reel'  => 'decimal:2',
        'etat'          => 'integer',
    ];

    public function projet()   { return $this->belongsTo(Projet::class); }
    public function depenses() { return $this->hasMany(\App\Domain\Finances\Models\Depense::class, 'ligne_budget_id'); }

    public function getEcartAttribute(): float
    {
        return (float) $this->montant_prevu - (float) $this->montant_reel;
    }

    public function getTauxConsommationAttribute(): float
    {
        return $this->montant_prevu > 0
            ? round(($this->montant_reel / $this->montant_prevu) * 100, 2)
            : 0;
    }

    public function getEstDepasseAttribute(): bool
    {
        return (float) $this->montant_reel > (float) $this->montant_prevu;
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeDepassees($q) { return $q->whereColumn('montant_reel', '>', 'montant_prevu'); }
}