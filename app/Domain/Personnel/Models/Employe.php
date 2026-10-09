<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $guarded = ['id'];
    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche'  => 'date',
        'salaire_base'   => 'decimal:2',
        'etat'           => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function user()        { return $this->belongsTo(\App\Domain\Socle\Models\User::class); }
    public function departement() { return $this->belongsTo(Departement::class); }
    public function poste()       { return $this->belongsTo(Poste::class); }

    public function contrats()       { return $this->hasMany(Contrat::class, 'employee_id'); }
    public function contratsActifs() { return $this->hasMany(Contrat::class, 'employee_id')->where('statut', 'en_cours'); }
    public function conges()         { return $this->hasMany(DemandeConge::class, 'employee_id'); }
    public function documents()      { return $this->hasMany(DocumentEmploye::class, 'employee_id'); }
    public function presences()      { return $this->hasMany(Presence::class, 'employee_id'); }
    public function bulletins()      { return $this->hasMany(BulletinPaie::class, 'employee_id'); }
    public function avances()        { return $this->hasMany(AvanceSalaire::class, 'employee_id'); }

    public function projets()
    {
        return $this->belongsToMany(
            \App\Domain\Execution\Models\Projet::class,
            'equipe_projets',
            'employee_id',
            'projet_id'
        )->withPivot(['role_chantier', 'date_debut', 'date_fin'])
         ->withTimestamps();
    }

    public function tachesAssignees()
    {
        return $this->hasMany(\App\Domain\Execution\Models\Tache::class, 'assigne_a');
    }

    public function depensesPayees()
    {
        return $this->hasMany(\App\Domain\Finances\Models\Depense::class, 'paye_par');
    }

    // ==================== ACCESSORS ====================
    public function getNomCompletAttribute(): string
    {
        return trim("{$this->nom} {$this->prenom}");
    }

    public function getInitialesAttribute(): string
    {
        return strtoupper(
            substr($this->nom ?? '', 0, 1) . substr($this->prenom ?? '', 0, 1)
        );
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance?->age;
    }

    public function getAncienneteAnneesAttribute(): ?int
    {
        return $this->date_embauche
            ? (int) $this->date_embauche->diffInYears(now())
            : null;
    }

    public function getTypeContratLabelAttribute(): string
    {
        return match ($this->type_contrat) {
            'cdi'         => 'CDI',
            'cdd'         => 'CDD',
            'journalier'  => 'Journalier',
            'stage'       => 'Stage',
            'prestataire' => 'Prestataire',
            default       => strtoupper($this->type_contrat),
        };
    }

    public function getSoldeCongesAttribute(): int
    {
        return (int) $this->conges()
            ->where('statut', 'approuve')
            ->whereYear('date_debut', now()->year)
            ->sum('nombre_jours');
    }

    public function getHeuresTravailleesMoisAttribute(): float
    {
        return (float) $this->presences()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNotNull('valide_le')
            ->sum('heures_travaillees');
    }

    public function getEstActifAttribute(): bool
    {
        return $this->statut === 'actif' && $this->etat === 1;
    }

    public function getEstJournalierAttribute(): bool
    {
        return $this->type_contrat === 'journalier';
    }

    public function getDocumentsExpiresAttribute()
    {
        return $this->documents()
            ->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '<', now())
            ->get();
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1)->where('statut', 'actif'); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeTypeContrat($q, $type) { return $q->where('type_contrat', $type); }
    public function scopeJournaliers($q) { return $q->where('type_contrat', 'journalier'); }
    public function scopePermanents($q) { return $q->where('type_contrat', 'cdi'); }
    public function scopeDepartement($q, int $id) { return $q->where('departement_id', $id); }
    public function scopeSearch($q, string $term)
    {
        return $q->where(fn($qq) => $qq
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('prenom', 'like', "%{$term}%")
            ->orWhere('matricule', 'like', "%{$term}%")
        );
    }
}