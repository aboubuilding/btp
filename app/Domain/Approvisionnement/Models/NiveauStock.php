<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiveauStock extends Model
{
    use HasFactory;

    protected $table = 'niveau_stocks';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite' => 'decimal:3',
        'cmup'     => 'decimal:2',
    ];

    public function entrepot() { return $this->belongsTo(Entrepot::class); }
    public function materiau() { return $this->belongsTo(Materiau::class); }

    public function getValeurAttribute(): float
    {
        return round((float) $this->quantite * (float) $this->cmup, 2);
    }

    public function getEstSousSeuilAttribute(): bool
    {
        return $this->materiau
            && (float) $this->quantite <= (float) $this->materiau->seuil_alerte_stock_min;
    }

    public function getEstCritiqueAttribute(): bool
    {
        return $this->materiau
            && (float) $this->quantite <= ((float) $this->materiau->seuil_alerte_stock_min * 0.5);
    }

    public function getStatutLabelAttribute(): string
    {
        if ($this->est_critique) return 'Critique';
        if ($this->est_sous_seuil) return 'Faible';
        return 'OK';
    }

    public function scopeSousSeuil($q)
    {
        return $q->whereRaw('quantite <= (SELECT seuil_alerte_stock_min FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)');
    }
}