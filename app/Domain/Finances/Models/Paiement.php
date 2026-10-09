<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_paiement' => 'date',
        'montant'       => 'decimal:2',
        'etat'          => 'integer',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo('payable', 'type_payable', 'id_payable');
    }

    public function compteBancaire() { return $this->belongsTo(CompteBancaire::class); }
    public function caisse()         { return $this->belongsTo(Caisse::class); }

    public function getEstEncaissementAttribute(): bool { return $this->sens === 'encaissement'; }
    public function getEstDecaissementAttribute(): bool { return $this->sens === 'decaissement'; }

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

    public function getSourceAttribute(): ?string
    {
        if ($this->compte_bancaire_id) return $this->compteBancaire?->libelle;
        if ($this->caisse_id) return $this->caisse?->libelle;
        return null;
    }

    public function scopeEncaissements($q) { return $q->where('sens', 'encaissement'); }
    public function scopeDecaissements($q) { return $q->where('sens', 'decaissement'); }
    public function scopePeriode($q, $debut, $fin) { return $q->whereBetween('date_paiement', [$debut, $fin]); }
}