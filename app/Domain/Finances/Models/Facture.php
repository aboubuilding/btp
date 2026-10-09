<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';
    protected $guarded = ['id'];
    protected $casts = [
        'date_facture'         => 'date',
        'date_echeance'        => 'date',
        'montant_ht'           => 'decimal:2',
        'tva'                  => 'decimal:2',
        'montant_ttc'          => 'decimal:2',
        'retenue_garantie'     => 'decimal:2',
        'remboursement_avance' => 'decimal:2',
        'net_a_payer'          => 'decimal:2',
        'montant_paye'         => 'decimal:2',
        'etat'                 => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function projet()       { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function situation()    { return $this->belongsTo(\App\Domain\Execution\Models\Situation::class); }
    public function bonCommande()  { return $this->belongsTo(\App\Domain\Approvisionnement\Models\BonCommande::class); }

    public function facturable(): MorphTo
    {
        return $this->morphTo('facturable', 'type_facturable', 'id_facturable');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_payable')
            ->where('type_payable', self::class);
    }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    // ==================== ACCESSORS ====================
    public function getEstClientAttribute(): bool      { return $this->type === 'client'; }
    public function getEstFournisseurAttribute(): bool { return $this->type === 'fournisseur'; }

    public function getResteAPayerAttribute(): float
    {
        return max(0, (float) $this->net_a_payer - (float) $this->montant_paye);
    }

    public function getEstSoldeeAttribute(): bool
    {
        return (float) $this->montant_paye >= (float) $this->net_a_payer;
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->date_echeance
            && $this->date_echeance->isPast()
            && (float) $this->montant_paye < (float) $this->net_a_payer
            && $this->statut !== 'annulee';
    }

    public function getJoursRetardAttribute(): int
    {
        return $this->est_en_retard
            ? (int) $this->date_echeance->diffInDays(now())
            : 0;
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'emise'              => 'Émise',
            'partiellement_payee'=> 'Partiellement payée',
            'payee'              => 'Payée',
            'annulee'            => 'Annulée',
            default              => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'payee'              => 'success',
            'partiellement_payee'=> 'warning',
            'emise'              => 'info',
            'annulee'            => 'danger',
            default              => 'default',
        };
    }

    public function getTauxPaiementAttribute(): float
    {
        return $this->net_a_payer > 0
            ? round(($this->montant_paye / $this->net_a_payer) * 100, 2)
            : 0;
    }

    // ==================== METHODS ====================
    public function recalculerStatut(): void
    {
        $paye = (float) $this->montant_paye;
        $du = (float) $this->net_a_payer;

        if ($paye >= $du) {
            $this->statut = 'payee';
        } elseif ($paye > 0) {
            $this->statut = 'partiellement_payee';
        } else {
            $this->statut = 'emise';
        }
        $this->save();
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeClients($q) { return $q->where('type', 'client'); }
    public function scopeFournisseurs($q) { return $q->where('type', 'fournisseur'); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeImpayees($q)
    {
        return $q->whereIn('statut', ['emise', 'partiellement_payee']);
    }
    public function scopeEnRetard($q)
    {
        return $q->whereIn('statut', ['emise', 'partiellement_payee'])
            ->whereDate('date_echeance', '<', now())
            ->whereRaw('montant_paye < net_a_payer');
    }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopePeriode($q, $debut, $fin) { return $q->whereBetween('date_facture', [$debut, $fin]); }
}