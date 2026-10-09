<?php
namespace App\Domain\QHSE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PvReception extends Model
{
    use HasFactory;

    protected $table = 'pv_receptions';
    protected $guarded = ['id'];
    protected $casts = [
        'date_reception' => 'date',
        'avec_reserves'  => 'boolean',
        'etat'           => 'integer',
    ];

    public function projet()   { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function reserves() { return $this->hasMany(Reserve::class, 'pv_reception_id'); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'partielle'   => 'Réception partielle',
            'provisoire'  => 'Réception provisoire',
            'definitive'  => 'Réception définitive',
            default       => ucfirst($this->type),
        };
    }

    public function getNbReservesOuvertesAttribute(): int
    {
        return $this->reserves()->where('statut', 'ouverte')->count();
    }

    public function getNbReservesLeveesAttribute(): int
    {
        return $this->reserves()->where('statut', 'levee')->count();
    }

    public function getToutesReservesLeveesAttribute(): bool
    {
        return $this->nb_reserves_ouvertes === 0;
    }

    public function getEstSigneAttribute(): bool { return $this->statut === 'signe'; }
    public function getEstBrouillonAttribute(): bool { return $this->statut === 'brouillon'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'signe'     => 'Signé',
            default     => ucfirst($this->statut),
        };
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeSignes($q) { return $q->where('statut', 'signe'); }
    public function scopeDefinitifs($q) { return $q->where('type', 'definitive'); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
}