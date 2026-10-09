<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    use HasFactory;

    protected $table = 'demande_conges';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'   => 'date',
        'date_fin'     => 'date',
        'nombre_jours' => 'integer',
        'approuve_le'  => 'datetime',
    ];

    public function employe()     { return $this->belongsTo(Employe::class, 'employee_id'); }
    public function typeConge()   { return $this->belongsTo(TypeConge::class); }
    public function approuvePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'approuve_par'); }

    public function getEstEnAttenteAttribute(): bool { return $this->statut === 'demande'; }
    public function getEstApprouveeAttribute(): bool { return $this->statut === 'approuve'; }
    public function getEstRefuseeAttribute(): bool   { return $this->statut === 'refuse'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'demande'  => 'En attente',
            'approuve' => 'Approuvé',
            'refuse'   => 'Refusé',
            default    => ucfirst($this->statut),
        };
    }

    public function getDureeJoursAttribute(): int
    {
        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    public function scopeEnAttente($q) { return $q->where('statut', 'demande'); }
    public function scopeApprouvees($q) { return $q->where('statut', 'approuve'); }
    public function scopeDeLEmploye($q, int $employeId) { return $q->where('employee_id', $employeId); }
}