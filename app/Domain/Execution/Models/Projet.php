<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Projet extends Model
{
    use HasFactory;

    protected $table = 'projets';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut_prevue'         => 'date',
        'date_fin_prevue'           => 'date',
        'date_debut_reelle'         => 'date',
        'date_fin_reelle'           => 'date',
        'date_reception_provisoire' => 'date',
        'date_reception_definitive' => 'date',
        'latitude'                  => 'decimal:7',
        'longitude'                 => 'decimal:7',
        'budget_prevu'              => 'decimal:2',
        'budget_reel'               => 'decimal:2',
        'montant_contrat'           => 'decimal:2',
        'pourcentage_avancement'    => 'integer',
        'etat'                      => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function client()      { return $this->belongsTo(\App\Domain\Commercial\Models\Client::class); }
    public function marche()      { return $this->belongsTo(\App\Domain\Commercial\Models\Marche::class); }
    public function conducteur()  { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'conducteur_travaux_id'); }
    public function chefChantier(){ return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'chef_chantier_id'); }

    public function phases()      { return $this->hasMany(PhaseProjet::class); }
    public function taches()      { return $this->hasMany(Tache::class); }
    public function jalons()      { return $this->hasMany(JalonProjet::class); }
    public function ligneBudgets(){ return $this->hasMany(LigneBudget::class); }
    public function avancements() { return $this->hasMany(AvancementProjet::class); }
    public function attachements(){ return $this->hasMany(Attachement::class); }
    public function situations()  { return $this->hasMany(Situation::class); }
    public function entrepots()   { return $this->hasMany(\App\Domain\Approvisionnement\Models\Entrepot::class); }
    public function caisses()     { return $this->hasMany(\App\Domain\Finances\Models\Caisse::class); }
    public function depenses()    { return $this->hasMany(\App\Domain\Finances\Models\Depense::class); }
    public function mouvementsStock() { return $this->hasMany(\App\Domain\Approvisionnement\Models\MouvementStock::class); }
    public function affectationsEquipements() { return $this->hasMany(\App\Domain\ParcMateriel\Models\AffectationEquipement::class); }
    public function presences()   { return $this->hasMany(\App\Domain\Personnel\Models\Presence::class); }
    public function contratsSousTraitants() { return $this->hasMany(\App\Domain\SousTraitance\Models\ContratSousTraitant::class); }
    public function incidents()   { return $this->hasMany(\App\Domain\QHSE\Models\IncidentSecurite::class); }
    public function pvReceptions() { return $this->hasMany(\App\Domain\QHSE\Models\PvReception::class); }
    public function documents()   { return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable'); }

    public function equipe(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Domain\Personnel\Models\Employe::class,
            'equipe_projets',
            'projet_id',
            'employee_id'
        )->withPivot(['role_chantier', 'date_debut', 'date_fin'])
         ->withTimestamps();
    }

    // ==================== ACCESSORS ====================
    public function getAvancementPondereAttribute(): float
    {
        $taches = $this->taches()->where('etat', 1)->with('ligneDevis')->get();
        if ($taches->isEmpty()) return 0.0;

        $poidsTotal = $taches->sum(fn($t) => (float) ($t->ligneDevis?->montant ?? 1));
        if ($poidsTotal <= 0) return 0.0;

        $poidsAvance = $taches->sum(fn($t) =>
            ((float) ($t->ligneDevis?->montant ?? 1)) * ($t->pourcentage_avancement / 100)
        );

        return round(($poidsAvance / $poidsTotal) * 100, 2);
    }

    public function getEcartBudgetAttribute(): float
    {
        return (float) $this->budget_prevu - (float) $this->budget_reel;
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->date_fin_prevue
            && $this->date_fin_prevue->isPast()
            && $this->statut !== 'termine'
            && $this->pourcentage_avancement < 100;
    }

    public function getJoursRestantsAttribute(): ?int
    {
        return $this->date_fin_prevue
            ? max(0, (int) now()->diffInDays($this->date_fin_prevue, false))
            : null;
    }

    public function getConsommationDelaiAttribute(): ?float
    {
        if (!$this->date_debut_reelle || !$this->date_fin_prevue) return null;
        $dureeTotale = $this->date_debut_reelle->diffInDays($this->date_fin_prevue);
        if ($dureeTotale <= 0) return 100;
        $joursEcoules = $this->date_debut_reelle->diffInDays(now());
        return round(min(100, ($joursEcoules / $dureeTotale) * 100), 2);
    }

    public function getCoordonneesGpsAttribute(): ?string
    {
        return $this->latitude && $this->longitude
            ? "{$this->latitude},{$this->longitude}"
            : null;
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)   { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeEnCours($q) { return $q->where('statut', 'en_cours'); }
    public function scopePlanifies($q) { return $q->where('statut', 'planifie'); }
    public function scopeTermines($q) { return $q->where('statut', 'termine'); }
    public function scopeEnRetard($q)
    {
        return $q->where('statut', 'en_cours')
            ->whereDate('date_fin_prevue', '<', now())
            ->where('pourcentage_avancement', '<', 100);
    }
    public function scopeType($q, $type) { return $q->where('type', $type); }
    public function scopeSearch($q, string $term)
    {
        return $q->where(fn($qq) => $qq
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
        );
    }
}