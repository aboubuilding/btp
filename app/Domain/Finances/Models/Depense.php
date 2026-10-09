<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $table = 'depenses';
    protected $guarded = ['id'];
    protected $casts = [
        'date_depense' => 'date',
        'montant'      => 'decimal:2',
        'approuve_le'  => 'datetime',
        'etat'         => 'integer',
    ];

    public function projet()       { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function ligneBudget()  { return $this->belongsTo(\App\Domain\Execution\Models\LigneBudget::class, 'ligne_budget_id'); }
    public function caisse()       { return $this->belongsTo(Caisse::class); }
    public function payePar()      { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'paye_par'); }
    public function approuvePar()  { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'approuve_par'); }

    public function getEstApprouveeAttribute(): bool { return $this->statut === 'approuve'; }
    public function getEstEnAttenteAttribute(): bool { return $this->statut === 'en_attente'; }
    public function getEstRejeteeAttribute(): bool   { return $this->statut === 'rejete'; }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'approuve'   => 'Approuvée',
            'rejete'     => 'Rejetée',
            default      => ucfirst($this->statut),
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'approuve'   => 'success',
            'rejete'     => 'danger',
            'en_attente' => 'warning',
            default      => 'default',
        };
    }

    public function getEstJustifieeAttribute(): bool
    {
        return !empty($this->document_justificatif);
    }

    public function getCategorieLabelAttribute(): string
    {
        return match ($this->categorie) {
            'carburant'       => 'Carburant',
            'materiaux'       => 'Matériaux',
            'sous_traitance'  => 'Sous-traitance',
            'frais_generaux'  => 'Frais généraux',
            'autre'           => 'Autre',
            default           => ucfirst(str_replace('_', ' ', $this->categorie)),
        };
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeEnAttente($q) { return $q->where('statut', 'en_attente'); }
    public function scopeApprouvees($q) { return $q->where('statut', 'approuve'); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopePeriode($q, $debut, $fin) { return $q->whereBetween('date_depense', [$debut, $fin]); }
}