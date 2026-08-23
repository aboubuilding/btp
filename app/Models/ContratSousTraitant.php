<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'contrat_sous_traitants';

    protected $fillable = [
        'sous_traitant_id',
        'projet_id',
        'numero_contrat',
        'description',
        'montant',
        'date_debut',
        'date_fin',
        'statut',
        'chemin_fichier',
        'etat',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'statut' => 'en_cours',
        'etat' => 1,
    ];

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuts(): array
    {
        return [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'resilie' => 'Résilié',
        ];
    }

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
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->statut) {
            'en_cours' => 'success',
            'termine' => 'info',
            'resilie' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuts()[$this->statut] ?? $this->statut;
    }

    /**
     * Vérifier si le contrat est en cours
     */
    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours' && $this->etat === 1;
    }

    /**
     * Vérifier si le contrat est terminé
     */
    public function isTermine(): bool
    {
        return $this->statut === 'termine';
    }

    /**
     * Vérifier si le contrat est résilié
     */
    public function isResilie(): bool
    {
        return $this->statut === 'resilie';
    }

    /**
     * Get montant formaté
     */
    public function getMontantFormattedAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get durée en jours
     */
    public function getDureeAttribute(): ?int
    {
        if (!$this->date_fin) {
            return null;
        }
        return $this->date_debut->diffInDays($this->date_fin);
    }

    /**
     * Get statut du fichier
     */
    public function getHasFileAttribute(): bool
    {
        return !empty($this->chemin_fichier) && file_exists(storage_path('app/public/' . $this->chemin_fichier));
    }
}
