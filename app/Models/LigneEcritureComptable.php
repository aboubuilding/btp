<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneEcritureComptable extends Model
{
    use HasFactory;

    protected $table = 'ligne_ecriture_comptables';

    protected $fillable = [
        'ecriture_comptable_id',
        'compte_id',
        'debit',
        'credit',
        'description',
        'etat',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'etat' => 1,
    ];

    /**
     * Relation avec l'écriture comptable
     */
    public function ecriture()
    {
        return $this->belongsTo(EcritureComptable::class);
    }

    /**
     * Relation avec le compte comptable
     */
    public function compte()
    {
        return $this->belongsTo(PlanComptable::class, 'compte_id');
    }

    /**
     * Get montant (débit ou crédit)
     */
    public function getMontantAttribute(): float
    {
        return $this->debit > 0 ? $this->debit : $this->credit;
    }

    /**
     * Get sens (Débit ou Crédit)
     */
    public function getSensAttribute(): string
    {
        return $this->debit > 0 ? 'Débit' : 'Crédit';
    }

    /**
     * Get montant formaté
     */
    public function getMontantFormattedAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
}
