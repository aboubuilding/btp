<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soustraitant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soustraitants';

    protected $fillable = [
        'nom_entreprise',
        'personne_contact',
        'telephone',
        'email',
        'adresse',
        'specialite',
        'note',
        'statut',
        'etat',
    ];

    protected $casts = [
        'note' => 'integer',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'statut' => 'actif',
        'etat' => 1,
    ];

    /**
     * Liste des spécialités disponibles
     */
    public static function getSpecialites(): array
    {
        return [
            'electricite' => 'Électricité',
            'plomberie' => 'Plomberie',
            'etancheite' => 'Étanchéité',
            'menuiserie' => 'Menuiserie',
            'ferronnerie' => 'Ferronnerie',
            'peinture' => 'Peinture',
            'carrelage' => 'Carrelage',
            'maçonnerie' => 'Maçonnerie',
            'charpente' => 'Charpente',
            'climatisation' => 'Climatisation',
            'serrurerie' => 'Serrurerie',
            'autres' => 'Autres',
        ];
    }

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuts(): array
    {
        return [
            'actif' => 'Actif',
            'suspendu' => 'Suspendu',
            'blackliste' => 'Blacklisté',
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->statut) {
            'actif' => 'success',
            'suspendu' => 'warning',
            'blackliste' => 'danger',
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
     * Get specialite label
     */
    public function getSpecialiteLabelAttribute(): string
    {
        return self::getSpecialites()[$this->specialite] ?? $this->specialite;
    }

    /**
     * Vérifier si le sous-traitant est actif
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif' && $this->etat === 1;
    }

    /**
     * Vérifier si le sous-traitant est suspendu
     */
    public function isSuspended(): bool
    {
        return $this->statut === 'suspendu';
    }

    /**
     * Vérifier si le sous-traitant est blacklisté
     */
    public function isBlacklisted(): bool
    {
        return $this->statut === 'blackliste';
    }
}
