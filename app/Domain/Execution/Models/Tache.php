<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $table = 'taches';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'             => 'date',
        'date_fin'               => 'date',
        'duree_jours'            => 'integer',
        'pourcentage_avancement' => 'integer',
        'etat'                   => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function projet()      { return $this->belongsTo(Projet::class); }
    public function phase()       { return $this->belongsTo(PhaseProjet::class, 'phase_id'); }
    public function ligneDevis()  { return $this->belongsTo(\App\Domain\Commercial\Models\LigneDevis::class, 'ligne_devis_id'); }
    public function assigne()     { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'assigne_a'); }

    public function dependances()
    {
        return $this->hasMany(DependanceTache::class, 'tache_id');
    }

    public function dependDe()
    {
        return $this->belongsToMany(
            Tache::class,
            'dependance_taches',
            'tache_id',
            'depend_de_tache_id'
        )->withPivot('type');
    }

    public function dependants()
    {
        return $this->belongsToMany(
            Tache::class,
            'dependance_taches',
            'depend_de_tache_id',
            'tache_id'
        )->withPivot('type');
    }

    // ==================== ACCESSORS ====================
    public function getEstEnRetardAttribute(): bool
    {
        return $this->date_fin
            && $this->date_fin->isPast()
            && $this->pourcentage_avancement < 100
            && $this->statut !== 'termine';
    }

    public function getPrioriteLabelAttribute(): string
    {
        return match ($this->priorite) {
            'basse'    => 'Basse',
            'normale'  => 'Normale',
            'haute'    => 'Haute',
            'critique' => 'Critique',
            default    => ucfirst($this->priorite ?? '—'),
        };
    }

    public function getCouleurPrioriteAttribute(): string
    {
        return match ($this->priorite) {
            'basse'    => '#94a3b8',
            'normale'  => '#2b6cb0',
            'haute'    => '#b7950b',
            'critique' => '#e63946',
            default    => '#6b7a8f',
        };
    }

    public function getEstEnCoursAttribute(): bool
    {
        return $this->statut === 'en_cours';
    }

    public function getEstTermineeAttribute(): bool
    {
        return $this->statut === 'termine';
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)   { return $q->where('etat', 1); }
    public function scopeEnRetard($q)
    {
        return $q->whereDate('date_fin', '<', now())
            ->where('pourcentage_avancement', '<', 100)
            ->where('statut', '!=', 'termine');
    }
    public function scopeEnCours($q)  { return $q->where('statut', 'en_cours'); }
    public function scopeTermines($q) { return $q->where('statut', 'termine'); }
    public function scopeAFaire($q)   { return $q->where('statut', 'a_faire'); }
    public function scopePriorite($q, $p) { return $q->where('priorite', $p); }
    public function scopeAssigneA($q, int $employeId) { return $q->where('assigne_a', $employeId); }
}