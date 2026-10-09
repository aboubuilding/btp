<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiau extends Model
{
    use HasFactory;

    protected $table = 'materiaux';
    protected $guarded = ['id'];
    protected $casts = [
        'prix_unitaire'          => 'decimal:2',
        'seuil_alerte_stock_min' => 'decimal:2',
        'etat'                   => 'integer',
    ];

    public function categorie()  { return $this->belongsTo(CategorieMateriau::class, 'categorie_id'); }
    public function niveaux()    { return $this->hasMany(NiveauStock::class); }
    public function mouvements() { return $this->hasMany(MouvementStock::class); }

    public function getQuantiteTotaleAttribute(): float
    {
        return (float) $this->niveaux()->sum('quantite');
    }

    public function getEstSousSeuilAttribute(): bool
    {
        return $this->quantite_totale <= (float) $this->seuil_alerte_stock_min;
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeSousSeuil($q)
    {
        return $q->whereRaw(
            '(SELECT COALESCE(SUM(quantite), 0) FROM niveau_stocks WHERE niveau_stocks.materiau_id = materiaux.id) <= seuil_alerte_stock_min'
        );
    }
    public function scopeSearch($q, string $term)
    {
        return $q->where(fn($qq) => $qq
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
        );
    }
}