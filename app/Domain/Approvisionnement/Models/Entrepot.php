<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    use HasFactory;

    protected $table = 'entrepots';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function projet()    { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function niveaux()   { return $this->hasMany(NiveauStock::class); }
    public function mouvements(){ return $this->hasMany(MouvementStock::class); }
    public function livraisons(){ return $this->hasMany(Livraison::class); }

    public function getEstCentralAttribute(): bool
    {
        return is_null($this->projet_id);
    }

    public function getValeurTotaleAttribute(): float
    {
        return (float) $this->niveaux()->sum(\DB::raw('quantite * cmup'));
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeCentraux($q) { return $q->whereNull('projet_id'); }
    public function scopeChantier($q, int $projetId) { return $q->where('projet_id', $projetId); }
}