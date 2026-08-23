<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'evaluation_sous_traitants';

    protected $fillable = [
        'sous_traitant_id',
        'projet_id',
        'evaluer_par',
        'note_qualite',
        'note_delai',
        'note_securite',
        'commentaires',
        'date_evaluation',
        'etat',
    ];

    protected $casts = [
        'note_qualite' => 'integer',
        'note_delai' => 'integer',
        'note_securite' => 'integer',
        'date_evaluation' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'etat' => 1,
    ];

    /**
     * Relation avec le sous-traitant
     */
    public function sousTraitant()
    {
        return $this->belongsTo(Soustraitant::class);
    }

    /**
     * Relation avec le projet
     */
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    /**
     * Relation avec l'utilisateur qui a évalué
     */
    public function evaluateur()
    {
        return $this->belongsTo(User::class, 'evaluer_par');
    }

    /**
     * Get note moyenne
     */
    public function getNoteMoyenneAttribute(): float
    {
        $notes = [$this->note_qualite, $this->note_delai, $this->note_securite];
        return round(array_sum($notes) / count($notes), 1);
    }

    /**
     * Get note qualite label
     */
    public function getNoteQualiteLabelAttribute(): string
    {
        return $this->getNoteLabel($this->note_qualite);
    }

    /**
     * Get note delai label
     */
    public function getNoteDelaiLabelAttribute(): string
    {
        return $this->getNoteLabel($this->note_delai);
    }

    /**
     * Get note securite label
     */
    public function getNoteSecuriteLabelAttribute(): string
    {
        return $this->getNoteLabel($this->note_securite);
    }

    /**
     * Get note label
     */
    private function getNoteLabel(int $note): string
    {
        return match ($note) {
            5 => 'Excellent ⭐⭐⭐⭐⭐',
            4 => 'Très bon ⭐⭐⭐⭐',
            3 => 'Bon ⭐⭐⭐',
            2 => 'Moyen ⭐⭐',
            1 => 'Médiocre ⭐',
            default => 'Non évalué',
        };
    }

    /**
     * Get note badge class
     */
    public function getNoteBadgeAttribute(): string
    {
        $moyenne = $this->note_moyenne;
        return match (true) {
            $moyenne >= 4.5 => 'success',
            $moyenne >= 3.5 => 'primary',
            $moyenne >= 2.5 => 'warning',
            default => 'danger',
        };
    }

    /**
     * Get note moyenne color
     */
    public function getNoteMoyenneColorAttribute(): string
    {
        $moyenne = $this->note_moyenne;
        return match (true) {
            $moyenne >= 4.5 => '#2d8f5e',
            $moyenne >= 3.5 => '#2b6cb0',
            $moyenne >= 2.5 => '#b7950b',
            default => '#c0392b',
        };
    }

    /**
     * Get date formatée
     */
    public function getDateFormattedAttribute(): string
    {
        return $this->date_evaluation ? $this->date_evaluation->format('d/m/Y') : '-';
    }

    /**
     * Vérifier si l'évaluation est active
     */
    public function isActive(): bool
    {
        return $this->etat === 1;
    }
}
