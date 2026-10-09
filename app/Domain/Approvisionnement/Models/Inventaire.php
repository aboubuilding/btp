<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    protected $table = 'inventaires';
    protected $guarded = ['id'];
    protected $casts = [
        'date_inventaire' => 'date',
        'valide_le'       => 'datetime',
    ];

    public function entrepot()  { return $this->belongsTo(Entrepot::class); }
    public function articles()  { return $this->hasMany(ArticleInventaire::class); }
    public function validePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function getEstValideAttribute(): bool { return $this->statut === 'valide'; }

    public function getEcartTotalAttribute(): float
    {
        return (float) $this->articles()->sum('ecart');
    }

    public function getValeurEcartAttribute(): float
    {
        return (float) $this->articles->sum(fn($a) =>
            (float) $a->ecart * (float) ($a->materiau?->prix_unitaire ?? 0)
        );
    }

    public function scopeValides($q) { return $q->where('statut', 'valide'); }
    public function scopeBrouillons($q) { return $q->where('statut', 'brouillon'); }
}