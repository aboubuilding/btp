<?php
namespace App\Domain\QHSE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentSecurite extends Model
{
    use HasFactory;

    protected $table = 'incident_securites';
    protected $guarded = ['id'];
    protected $casts = [
        'date_incident'    => 'datetime',
        'nombre_victimes'  => 'integer',
        'jours_arret'      => 'integer',
        'etat'             => 'integer',
    ];

    public function projet()     { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function declarePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'declare_par'); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    // ==================== ACCESSORS ====================
    public function getEstGraveAttribute(): bool
    {
        return in_array($this->gravite, ['grave', 'mortelle'], true);
    }

    public function getEstMortelAttribute(): bool
    {
        return $this->gravite === 'mortelle';
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'accident_travail'   => 'Accident du travail',
            'presque_accident'   => 'Presque-accident',
            'incident_materiel'  => 'Incident matériel',
            'environnement'      => 'Environnement',
            default              => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getGraviteLabelAttribute(): string
    {
        return match ($this->gravite) {
            'mineure'  => 'Mineure',
            'moyenne'  => 'Moyenne',
            'grave'    => 'Grave',
            'mortelle' => 'Mortelle',
            default    => ucfirst($this->gravite),
        };
    }

    public function getCouleurGraviteAttribute(): string
    {
        return match ($this->gravite) {
            'mineure'  => 'info',
            'moyenne'  => 'warning',
            'grave'    => 'danger',
            'mortelle' => 'danger',
            default    => 'default',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'declare'    => 'Déclaré',
            'en_analyse' => 'En analyse',
            'clos'       => 'Clos',
            default      => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getEstClosAttribute(): bool { return $this->statut === 'clos'; }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeGraves($q) { return $q->whereIn('gravite', ['grave', 'mortelle']); }
    public function scopeRecents($q, int $jours = 30) { return $q->where('date_incident', '>=', now()->subDays($jours)); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopeOuverts($q) { return $q->whereIn('statut', ['declare', 'en_analyse']); }
}