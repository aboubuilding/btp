<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteBancaire extends Model
{
    use HasFactory;

    protected $table = 'compte_bancaires';
    protected $guarded = ['id'];
    protected $casts = [
        'solde_initial' => 'decimal:2',
        'solde_actuel'  => 'decimal:2',
        'etat'          => 'integer',
    ];

    public function paiements() { return $this->hasMany(Paiement::class); }

    public function getVariationAttribute(): float
    {
        return (float) $this->solde_actuel - (float) $this->solde_initial;
    }

    public function getLibelleCompletAttribute(): string
    {
        return "{$this->libelle} ({$this->banque})";
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
}