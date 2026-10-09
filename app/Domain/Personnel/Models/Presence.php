<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';
    protected $guarded = ['id'];
    protected $casts = [
        'date'                   => 'date',
        'heures_travaillees'     => 'decimal:2',
        'heures_supplementaires' => 'decimal:2',
        'latitude'               => 'decimal:7',
        'longitude'              => 'decimal:7',
        'valide_le'              => 'datetime',
        'etat'                   => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function employe()        { return $this->belongsTo(Employe::class, 'employee_id'); }
    public function projet()         { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function enregistrePar()  { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'enregistre_par'); }
    public function validePar()      { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    // ==================== ACCESSORS ====================
    public function getEstValideeAttribute(): bool
    {
        return !is_null($this->valide_le);
    }

    public function getEstPresentAttribute(): bool
    {
        return $this->statut === 'present';
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'present' => 'Présent',
            'absent'  => 'Absent',
            'retard'  => 'Retard',
            'conge'   => 'Congé',
            'maladie' => 'Maladie',
            'ferie'   => 'Férié',
            default   => ucfirst($this->statut),
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'present' => 'success',
            'absent'  => 'danger',
            'retard'  => 'warning',
            'conge'   => 'info',
            'maladie' => 'info',
            'ferie'   => 'default',
            default   => 'default',
        };
    }

    public function getCoordonneesGpsAttribute(): ?string
    {
        return $this->latitude && $this->longitude
            ? "{$this->latitude},{$this->longitude}"
            : null;
    }

    // ==================== SCOPES ====================
    public function scopeValidees($q) { return $q->whereNotNull('valide_le'); }
    public function scopeEnAttente($q) { return $q->whereNull('valide_le'); }
    public function scopePresent($q) { return $q->where('statut', 'present'); }
    public function scopePeriode($q, $debut, $fin) { return $q->whereBetween('date', [$debut, $fin]); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopeDeLEmploye($q, int $employeId) { return $q->where('employee_id', $employeId); }
}