<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Model;

class LigneEcritureComptable extends Model
{
    protected $table = 'ligne_ecriture_comptables';
    protected $guarded = ['id'];
    protected $casts = [
        'debit'  => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function ecriture() { return $this->belongsTo(EcritureComptable::class); }
    public function compte()   { return $this->belongsTo(PlanComptable::class, 'plan_comptable_id'); }

    public function getSensAttribute(): string
    {
        if ((float) $this->debit > 0) return 'Débit';
        if ((float) $this->credit > 0) return 'Crédit';
        return '—';
    }
}