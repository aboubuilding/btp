<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalonProjet extends Model
{
    use HasFactory;

    protected $table = 'jalon_projets';
    protected $guarded = ['id'];
    protected $casts = [
        'date_echeance' => 'date',
        'date_atteinte' => 'date',
        'etat'          => 'integer',
    ];

    public function projet() { return $this->belongsTo(Projet::class); }

    public function getEstAtteintAttribute(): bool
    {
        return !is_null($this->date_atteinte);
    }

    public function getEstManqueAttribute(): bool
    {
        return !$this->date_atteinte
            && $this->date_echeance
            && $this->date_echeance->isPast();
    }

    public function getJoursRestantsAttribute(): ?int
    {
        return $this->date_echeance
            ? (int) now()->diffInDays($this->date_echeance, false)
            : null;
    }

    public function getStatutLabelAttribute(): string
    {
        if ($this->est_atteint) return 'Atteint';
        if ($this->est_manque)  return 'Manqué';
        return 'En attente';
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeAtteints($q) { return $q->whereNotNull('date_atteinte'); }
    public function scopeManques($q)
    {
        return $q->whereNull('date_atteinte')
            ->whereDate('date_echeance', '<', now());
    }
}