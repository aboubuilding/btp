<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseProjet extends Model
{
    use HasFactory;

    protected $table = 'phase_projets';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'ordre'      => 'integer',
        'etat'       => 'integer',
    ];

    public function projet() { return $this->belongsTo(Projet::class); }
    public function taches() { return $this->hasMany(Tache::class, 'phase_id'); }

    public function getPourcentageAvancementAttribute(): float
    {
        $taches = $this->taches;
        if ($taches->isEmpty()) return 0;
        return round($taches->avg('pourcentage_avancement') ?? 0, 2);
    }

    public function getEstTermineeAttribute(): bool
    {
        return $this->statut === 'termine';
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeOrdonnees($q) { return $q->orderBy('ordre'); }
}