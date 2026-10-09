<?php
namespace App\Domain\SousTraitance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soustraitant extends Model
{
    use HasFactory;

    protected $table = 'soustraitants';
    protected $guarded = ['id'];
    protected $casts = [
        'note' => 'decimal:1',
        'etat' => 'integer',
    ];

    public function contrats()    { return $this->hasMany(ContratSousTraitant::class, 'sous_traitant_id'); }
    public function evaluations() { return $this->hasMany(EvaluationSousTraitant::class, 'sous_traitant_id'); }

    public function getEstBlacklisteAttribute(): bool { return $this->statut === 'blackliste'; }
    public function getEstActifAttribute(): bool      { return $this->statut === 'actif'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'actif'      => 'Actif',
            'suspendu'   => 'Suspendu',
            'blackliste' => 'Blacklisté',
            default      => ucfirst($this->statut),
        };
    }

    public function getNoteMoyenneAttribute(): ?float
    {
        $moyenne = $this->evaluations->avg(function ($e) {
            return ((float) $e->note_qualite + (float) $e->note_delai + (float) $e->note_securite) / 3;
        });
        return $moyenne ? round($moyenne, 1) : null;
    }

    public function getMontantTotalContratsAttribute(): float
    {
        return (float) $this->contrats()->sum('montant');
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeActifs($q) { return $q->where('statut', 'actif'); }
    public function scopeBlacklistes($q) { return $q->where('statut', 'blackliste'); }
}