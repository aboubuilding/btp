<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    use HasFactory;

    protected $table = 'caisses';
    protected $guarded = ['id'];
    protected $casts = [
        'solde_initial' => 'decimal:2',
        'solde_actuel'  => 'decimal:2',
        'etat'          => 'integer',
    ];

    public function projet()    { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function paiements() { return $this->hasMany(Paiement::class); }
    public function depenses()  { return $this->hasMany(Depense::class); }

    public function getEstChantierAttribute(): bool { return !is_null($this->projet_id); }
    public function getEstSiegeAttribute(): bool    { return is_null($this->projet_id); }

    public function getVariationAttribute(): float
    {
        return (float) $this->solde_actuel - (float) $this->solde_initial;
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeChantier($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopeSiege($q) { return $q->whereNull('projet_id'); }
}