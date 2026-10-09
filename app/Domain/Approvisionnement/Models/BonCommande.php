<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonCommande extends Model
{
    use HasFactory;

    protected $table = 'bon_commands';
    protected $guarded = ['id'];
    protected $casts = [
        'date_commande'         => 'date',
        'date_livraison_prevue' => 'date',
        'montant_total'         => 'decimal:2',
        'valide_le'             => 'datetime',
        'etat'                  => 'integer',
    ];

    public function fournisseur() { return $this->belongsTo(Fournisseur::class); }
    public function demande()     { return $this->belongsTo(DemandeAchat::class, 'demande_achat_id'); }
    public function articles()    { return $this->hasMany(ArticleBonCommande::class); }
    public function livraisons()  { return $this->hasMany(Livraison::class); }
    public function validePar()   { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function factures()
    {
        return $this->hasMany(\App\Domain\Finances\Models\Facture::class, 'bon_commande_id');
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'brouillon'          => 'Brouillon',
            'envoye'             => 'Envoyé',
            'confirme'           => 'Confirmé',
            'livre_partiellement'=> 'Livré partiellement',
            'livre'              => 'Livré',
            'annule'             => 'Annulé',
            default              => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getEstFigeAttribute(): bool
    {
        return in_array($this->statut, ['livre', 'annule'], true);
    }

    public function getEstModifiableAttribute(): bool
    {
        return !$this->est_fige;
    }

    public function getTotalLivreAttribute(): float
    {
        return (float) $this->articles->sum(fn($a) => $a->quantite_recue);
    }

    public function getPourcentageLivreAttribute(): float
    {
        $total = (float) $this->articles->sum('quantite');
        return $total > 0 ? round(($this->total_livre / $total) * 100, 2) : 0;
    }

    // ==================== METHODS ====================
    public function recalculerTotal(): void
    {
        $this->montant_total = $this->articles()->sum('montant') ?? 0;
        $this->save();
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeEnCours($q)
    {
        return $q->whereIn('statut', ['envoye', 'confirme', 'livre_partiellement']);
    }
}