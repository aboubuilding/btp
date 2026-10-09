<?php
namespace App\Domain\QHSE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformite extends Model
{
    use HasFactory;

    protected $table = 'non_conformites';
    protected $guarded = ['id'];
    protected $casts = [
        'date_constat' => 'date',
        'date_levee'   => 'date',
        'etat'         => 'integer',
    ];

    public function projet()      { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function responsable() { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'responsable_id'); }

    public function getEstOuverteAttribute(): bool { return $this->statut === 'ouverte'; }
    public function getEstLeveeAttribute(): bool   { return $this->statut === 'levee'; }
    public function getEstEnTraitementAttribute(): bool { return $this->statut === 'en_traitement'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'ouverte'       => 'Ouverte',
            'en_traitement' => 'En traitement',
            'levee'         => 'Levée',
            default         => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'ouverte'       => 'danger',
            'en_traitement' => 'warning',
            'levee'         => 'success',
            default         => 'default',
        };
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->statut !== 'levee'
            && $this->date_levee
            && $this->date_levee->isPast();
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeOuvertes($q) { return $q->whereIn('statut', ['ouverte', 'en_traitement']); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
}