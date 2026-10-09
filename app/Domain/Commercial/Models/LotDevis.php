<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Model;

class LotDevis extends Model
{
    protected $table = 'lot_devis';
    protected $guarded = ['id'];
    protected $casts = [
        'ordre' => 'integer',
        'etat'  => 'integer',
    ];

    public function devis()   { return $this->belongsTo(Devis::class); }
    public function parent()  { return $this->belongsTo(self::class, 'parent_id'); }
    public function enfants() { return $this->hasMany(self::class, 'parent_id'); }
    public function lignes()  { return $this->hasMany(LigneDevis::class); }

    public function getTotalHtAttribute(): float
    {
        return (float) $this->lignes()->sum('montant');
    }

    public function scopeRacines($q) { return $q->whereNull('parent_id'); }
    public function scopeActif($q)   { return $q->where('etat', 1); }
}