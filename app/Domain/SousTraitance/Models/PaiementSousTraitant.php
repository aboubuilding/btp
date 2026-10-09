<?php
namespace App\Domain\SousTraitance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'paiement_sous_traitants';
    protected $guarded = ['id'];
    protected $casts = [
        'date_paiement' => 'date',
        'montant'       => 'decimal:2',
    ];

    public function facture() { return $this->belongsTo(FactureSousTraitant::class, 'facture_sous_traitant_id'); }

    public function getModeLabelAttribute(): string
    {
        return match ($this->mode) {
            'especes'      => 'Espèces',
            'cheque'       => 'Chèque',
            'virement'     => 'Virement',
            'mobile_money' => 'Mobile Money',
            default        => ucfirst(str_replace('_', ' ', $this->mode)),
        };
    }
}