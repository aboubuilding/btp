<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'paiement_sous_traitants';

    protected $fillable = [
        'facture_sous_traitant_id',
        'montant',
        'date_paiement',
        'mode',
        'reference',
        'etat',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'etat' => 1,
    ];

    /**
     * Liste des modes de paiement
     */
    public static function getModes(): array
    {
        return [
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement bancaire',
            'mobile_money' => 'Mobile Money',
        ];
    }

    /**
     * Relation avec la facture
     */
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'facture_sous_traitant_id');
    }

    /**
     * Relation avec le sous-traitant via la facture
     */
    public function sousTraitant()
    {
        return $this->hasOneThrough(
            Soustraitant::class,
            Facture::class,
            'id',
            'id',
            'facture_sous_traitant_id',
            'id_facturable'
        );
    }

    /**
     * Get mode label
     */
    public function getModeLabelAttribute(): string
    {
        return self::getModes()[$this->mode] ?? $this->mode;
    }

    /**
     * Get mode badge class
     */
    public function getModeBadgeAttribute(): string
    {
        return match ($this->mode) {
            'especes' => 'success',
            'cheque' => 'primary',
            'virement' => 'info',
            'mobile_money' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get montant formaté
     */
    public function getMontantFormattedAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get date formatée
     */
    public function getDateFormattedAttribute(): string
    {
        return $this->date_paiement ? $this->date_paiement->format('d/m/Y') : '-';
    }

    /**
     * Vérifier si le paiement est actif
     */
    public function isActive(): bool
    {
        return $this->etat === 1;
    }
}
