<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    protected $table = 'contrats';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'   => 'date',
        'date_fin'     => 'date',
        'salaire_base' => 'decimal:2',
    ];

    public function employe() { return $this->belongsTo(Employe::class, 'employee_id'); }

    public function getEstActifAttribute(): bool
    {
        return $this->statut === 'en_cours'
            && (!$this->date_fin || $this->date_fin->isFuture());
    }

    public function getJoursRestantsAttribute(): ?int
    {
        return $this->date_fin
            ? (int) now()->diffInDays($this->date_fin, false)
            : null;
    }

    public function getExpireBientotAttribute(): bool
    {
        return $this->date_fin
            && $this->statut === 'en_cours'
            && now()->diffInDays($this->date_fin, false) <= 30
            && $this->date_fin->isFuture();
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'cdi'         => 'CDI',
            'cdd'         => 'CDD',
            'journalier'  => 'Journalier',
            'stage'       => 'Stage',
            'prestataire' => 'Prestataire',
            default       => strtoupper($this->type),
        };
    }

    public function scopeActifs($q) { return $q->where('statut', 'en_cours'); }
    public function scopeExpirentBientot($q, int $jours = 30)
    {
        return $q->where('statut', 'en_cours')
            ->whereNotNull('date_fin')
            ->whereDate('date_fin', '<=', now()->addDays($jours));
    }
}