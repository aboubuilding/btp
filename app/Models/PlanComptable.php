<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanComptable extends Model
{
    use HasFactory;

    protected $table = 'plan_comptables';

    protected $fillable = [
        'code',
        'nom',
        'type',
        'parent_id',
        'etat',
    ];

    protected $casts = [
        'etat' => 'integer',
    ];

    protected $attributes = [
        'etat' => 1,
    ];

    /**
     * Types de comptes disponibles
     */
    public static function getTypes(): array
    {
        return [
            'actif' => 'Actif',
            'passif' => 'Passif',
            'charge' => 'Charge',
            'produit' => 'Produit',
            'capitaux' => 'Capitaux propres',
        ];
    }

    /**
     * Couleurs des types
     */
    public static function getTypeColors(): array
    {
        return [
            'actif' => 'success',
            'passif' => 'danger',
            'charge' => 'warning',
            'produit' => 'info',
            'capitaux' => 'primary',
        ];
    }

    /**
     * Icônes des types
     */
    public static function getTypeIcons(): array
    {
        return [
            'actif' => 'fa-building-columns',
            'passif' => 'fa-hand-holding-dollar',
            'charge' => 'fa-cart-shopping',
            'produit' => 'fa-chart-line',
            'capitaux' => 'fa-people-group',
        ];
    }

    /**
     * Relation avec le parent
     */
    public function parent()
    {
        return $this->belongsTo(PlanComptable::class, 'parent_id');
    }

    /**
     * Relation avec les enfants
     */
    public function enfants()
    {
        return $this->hasMany(PlanComptable::class, 'parent_id');
    }

    /**
     * Récupérer tous les enfants récursivement
     */
    public function allEnfants()
    {
        return $this->enfants()->with('allEnfants');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get type color
     */
    public function getTypeColorAttribute(): string
    {
        return self::getTypeColors()[$this->type] ?? 'secondary';
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return self::getTypeIcons()[$this->type] ?? 'fa-tag';
    }

    /**
     * Get parent code and name
     */
    public function getParentLabelAttribute(): string
    {
        if (!$this->parent_id) {
            return '<span class="text-muted">—</span>';
        }
        return $this->parent ? $this->parent->code . ' - ' . $this->parent->nom : 'N/A';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->etat === 1 ? 'success' : 'danger';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->etat === 1 ? 'Actif' : 'Inactif';
    }

    /**
     * Vérifier si le compte est actif
     */
    public function isActive(): bool
    {
        return $this->etat === 1;
    }

    /**
     * Vérifier si le compte est un compte de niveau 1
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Obtenir le niveau du compte (profondeur)
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        return $level;
    }

    /**
     * Obtenir le chemin complet du compte
     */
    public function getPathAttribute(): string
    {
        $path = [];
        $current = $this;
        while ($current) {
            array_unshift($path, $current->code);
            $current = $current->parent;
        }
        return implode(' > ', $path);
    }
}
