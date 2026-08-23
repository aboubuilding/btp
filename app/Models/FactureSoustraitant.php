<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureSoustraitant extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'numero_facture',
        'type',
        'type_facturable',
        'id_facturable',
        'projet_id',
        'date_facture',
        'date_echeance',
        'montant_ht',
        'tva',
        'montant_ttc',
        'statut',
        'etat',
    ];

    protected $casts = [
        'montant_ht' => 'decimal:2',
        'tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'date_facture' => 'date',
        'date_echeance' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'type' => 'fournisseur',
        'type_facturable' => 'Soustraitant',
        'statut' => 'emise',
        'etat' => 1,
    ];

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuts(): array
    {
        return [
            'emise' => 'Émise',
            'payee' => 'Payée',
            'partiellement_payee' => 'Partiellement payée',
            'annulee' => 'Annulée',
            'en_retard' => 'En retard',
        ];
    }

    /**
     * Liste des types
     */
    public static function getTypes(): array
    {
        return [
            'client' => 'Client',
            'fournisseur' => 'Fournisseur',
        ];
    }

    /**
     * Relation polymorphique avec le facturable (Soustraitant)
     */
    public function facturable()
    {
        return $this->morphTo();
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
            'emise' => 'primary',
            'payee' => 'success',
            'partiellement_payee' => 'warning',
            'annulee' => 'secondary',
            'en_retard' => 'danger',
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
     * Get montant HT formaté
     */
    public function getMontantHtFormattedAttribute(): string
    {
        return number_format($this->montant_ht, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get montant TTC formaté
     */
    public function getMontantTtcFormattedAttribute(): string
    {
        return number_format($this->montant_ttc, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Vérifier si la facture est en retard
     */
    public function isEnRetard(): bool
    {
        return $this->statut === 'en_retard' ||
            ($this->statut === 'emise' && $this->date_echeance && $this->date_echeance->isPast());
    }

    /**
     * Vérifier si la facture est payable
     */
    public function isPayable(): bool
    {
        return in_array($this->statut, ['emise', 'partiellement_payee']);
    }

    /**
     * Marquer comme payée
     */
    public function markAsPaid(): bool
    {
        $this->statut = 'payee';
        return $this->save();
    }

    /**
     * Marquer comme contestée
     */
    public function markAsContested(): bool
    {
        $this->statut = 'en_retard';
        return $this->save();
    }

    /**
     * Relation avec les paiements
     */
    public function paiements()
    {
        return $this->hasMany(PaiementSousTraitant::class, 'facture_sous_traitant_id');
    }

    /**
     * Get total payé pour cette facture
     */
    public function getTotalPayeAttribute(): float
    {
        return $this->paiements()->where('etat', 1)->sum('montant');
    }

    /**
     * Get reste à payer
     */
    public function getResteAPayerAttribute(): float
    {
        return max(0, $this->montant_ttc - $this->total_paye);
    }

    /**
     * Get pourcentage payé
     */
    public function getPourcentagePayeAttribute(): float
    {
        if ($this->montant_ttc == 0) {
            return 0;
        }
        return round(($this->total_paye / $this->montant_ttc) * 100, 1);
    }


}
