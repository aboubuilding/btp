<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinPaie extends Model
{
    use HasFactory;

    protected $table = 'bulletin_paies';
    protected $guarded = ['id'];
    protected $casts = [
        'salaire_base'            => 'decimal:2',
        'heures_travaillees'      => 'decimal:2',
        'heures_supplementaires'  => 'decimal:2',
        'montant_heures_sup'      => 'decimal:2',
        'primes'                  => 'decimal:2',
        'indemnites'              => 'decimal:2',
        'brut'                    => 'decimal:2',
        'cotisations_salariales'  => 'decimal:2',
        'cotisations_patronales'  => 'decimal:2',
        'impot_revenu'            => 'decimal:2',
        'avances_deduites'        => 'decimal:2',
        'net_a_payer'             => 'decimal:2',
    ];

    public function periode() { return $this->belongsTo(PeriodePaie::class, 'periode_paie_id'); }
    public function employe() { return $this->belongsTo(Employe::class, 'employee_id'); }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'valide'    => 'Validé',
            'paye'      => 'Payé',
            default     => ucfirst($this->statut),
        };
    }

    public function getTotalRetenuesAttribute(): float
    {
        return (float) $this->cotisations_salariales
             + (float) $this->impot_revenu
             + (float) $this->avances_deduites;
    }

    public function getCoutEmployeurAttribute(): float
    {
        return (float) $this->brut + (float) $this->cotisations_patronales;
    }

    public function scopeBrouillons($q) { return $q->where('statut', 'brouillon'); }
    public function scopeValides($q)    { return $q->where('statut', 'valide'); }
    public function scopePayes($q)      { return $q->where('statut', 'paye'); }
    public function scopeDeLEmploye($q, int $employeId) { return $q->where('employee_id', $employeId); }
}