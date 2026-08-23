<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcritureComptable extends Model
{
    use HasFactory;

    protected $table = 'ecriture_comptables';

    protected $fillable = [
        'exercice_fiscal_id',
        'numero_ecriture',
        'date_ecriture',
        'type_reference',
        'reference_id',
        'description',
        'cree_par',
        'statut',
        'etat',
    ];

    protected $casts = [
        'date_ecriture' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'statut' => 'brouillon',
        'etat' => 1,
    ];

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuts(): array
    {
        return [
            'brouillon' => 'Brouillon',
            'valide' => 'Validée',
        ];
    }

    /**
     * Liste des types de référence
     */
    public static function getTypesReference(): array
    {
        return [
            'invoice' => 'Facture',
            'expense' => 'Dépense',
            'payment' => 'Paiement',
            'payslip' => 'Bulletin de paie',
            'other' => 'Autre',
        ];
    }

    /**
     * Relation avec l'exercice fiscal
     */
    public function exerciceFiscal()
    {
        return $this->belongsTo(ExerciceFiscal::class);
    }

    /**
     * Relation avec les lignes d'écriture
     */
    public function lignes()
    {
        return $this->hasMany(LigneEcritureComptable::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Get statut badge class
     */
    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            'brouillon' => 'warning',
            'valide' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get statut label
     */
    public function getStatutLabelAttribute(): string
    {
        return self::getStatuts()[$this->statut] ?? $this->statut;
    }

    /**
     * Get type reference label
     */
    public function getTypeReferenceLabelAttribute(): string
    {
        return self::getTypesReference()[$this->type_reference] ?? $this->type_reference;
    }

    /**
     * Get total débit
     */
    public function getTotalDebitAttribute(): float
    {
        return $this->lignes()->where('etat', 1)->sum('debit');
    }

    /**
     * Get total crédit
     */
    public function getTotalCreditAttribute(): float
    {
        return $this->lignes()->where('etat', 1)->sum('credit');
    }

    /**
     * Get total débit formaté
     */
    public function getTotalDebitFormattedAttribute(): string
    {
        return number_format($this->total_debit, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get total crédit formaté
     */
    public function getTotalCreditFormattedAttribute(): string
    {
        return number_format($this->total_credit, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Vérifier si l'écriture est équilibrée
     */
    public function isEquilibree(): bool
    {
        return $this->total_debit == $this->total_credit;
    }

    /**
     * Vérifier si l'écriture est validée
     */
    public function isValidee(): bool
    {
        return $this->statut === 'valide';
    }

    /**
     * Vérifier si l'écriture est un brouillon
     */
    public function isBrouillon(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Valider l'écriture
     */
    public function valider(): bool
    {
        if (!$this->isEquilibree()) {
            return false;
        }
        $this->statut = 'valide';
        return $this->save();
    }

    /**
     * Contre-passer l'écriture (créer une écriture inverse)
     */
    public function contrePasser(): ?self
    {
        if (!$this->isValidee()) {
            return null;
        }

        $nouvelleEcriture = $this->replicate();
        $nouvelleEcriture->numero_ecriture = $this->generateNumeroContrePassation();
        $nouvelleEcriture->description = 'Contre-passation de ' . $this->numero_ecriture . ' - ' . ($this->description ?? '');
        $nouvelleEcriture->statut = 'valide';
        $nouvelleEcriture->date_ecriture = now();
        $nouvelleEcriture->save();

        foreach ($this->lignes as $ligne) {
            $nouvelleLigne = $ligne->replicate();
            $nouvelleLigne->ecriture_comptable_id = $nouvelleEcriture->id;
            $nouvelleLigne->debit = $ligne->credit;
            $nouvelleLigne->credit = $ligne->debit;
            $nouvelleLigne->save();
        }

        return $nouvelleEcriture;
    }

    /**
     * Générer un numéro d'écriture
     */
    public static function generateNumero(): string
    {
        $year = date('Y');
        $last = self::where('numero_ecriture', 'LIKE', "EC-{$year}-%")
            ->orderBy('numero_ecriture', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->numero_ecriture, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "EC-{$year}-{$newNumber}";
    }

    /**
     * Générer un numéro de contre-passation
     */
    private function generateNumeroContrePassation(): string
    {
        $year = date('Y');
        $base = "CP-{$year}-";
        $last = self::where('numero_ecriture', 'LIKE', "CP-{$year}-%")
            ->orderBy('numero_ecriture', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->numero_ecriture, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $base . $newNumber;
    }
}
