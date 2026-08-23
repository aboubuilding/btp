<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'type',
        'personne_contact',
        'telephone',
        'email',
        'adresse',
        'nif',
        'etat',
    ];

    protected $casts = [
        'etat' => 'integer',
    ];

    protected $attributes = [
        'type' => 'entreprise',
        'etat' => 1,
    ];

    /**
     * Liste des types de clients
     */
    public static function getTypes(): array
    {
        return [
            'particulier' => 'Particulier',
            'entreprise' => 'Entreprise',
            'public' => 'Public',
        ];
    }

    /**
     * Relation avec les projets
     */
    public function projets()
    {
        return $this->hasMany(Projet::class);
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'particulier' => 'info',
            'entreprise' => 'primary',
            'public' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->etat === 1 ? 'Actif' : 'Inactif';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->etat === 1 ? 'success' : 'danger';
    }

    /**
     * Vérifier si le client est actif
     */
    public function isActive(): bool
    {
        return $this->etat === 1;
    }

    /**
     * Compter le nombre de projets
     */
    public function getProjetsCountAttribute(): int
    {
        return $this->projets()->where('etat', 1)->count();
    }

    /**
     * Compter le nombre de projets en cours
     */
    public function getProjetsEnCoursAttribute(): int
    {
        return $this->projets()->where('etat', 1)->where('statut', 'en_cours')->count();
    }
}
