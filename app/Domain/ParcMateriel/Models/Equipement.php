<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $table = 'equipements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_achat'             => 'date',
        'prix_achat'             => 'decimal:2',
        'valeur_actuelle'        => 'decimal:2',
        'cout_horaire'           => 'decimal:2',
        'compteur_heures_actuel' => 'decimal:2',
        'etat'                   => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function categorie()          { return $this->belongsTo(CategorieEquipement::class, 'categorie_id'); }
    public function fournisseurLoueur()  { return $this->belongsTo(\App\Domain\Approvisionnement\Models\Fournisseur::class, 'fournisseur_loueur_id'); }
    public function projetActuel()       { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class, 'projet_actuel_id'); }

    public function affectations()       { return $this->hasMany(AffectationEquipement::class); }
    public function affectationOuverte() { return $this->hasOne(AffectationEquipement::class)->where('statut', 'ouverte'); }
    public function maintenances()       { return $this->hasMany(MaintenanceEquipement::class); }
    public function pannes()             { return $this->hasMany(PanneEquipement::class); }
    public function relevesCarburant()   { return $this->hasMany(ReleveCarburant::class); }
    public function documents()          { return $this->hasMany(DocumentEquipement::class); }

    public function documentsGeneraux()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    // ==================== ACCESSORS ====================
    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'disponible'     => 'Disponible',
            'en_service'     => 'En service',
            'en_panne'       => 'En panne',
            'en_maintenance' => 'En maintenance',
            'hors_service'   => 'Hors service',
            default          => ucfirst(str_replace('_', ' ', $this->statut)),
        };
    }

    public function getCouleurStatutAttribute(): string
    {
        return match ($this->statut) {
            'disponible'     => 'success',
            'en_service'     => 'info',
            'en_panne'       => 'danger',
            'en_maintenance' => 'warning',
            'hors_service'   => 'default',
            default          => 'default',
        };
    }

    public function getEstDisponibleAttribute(): bool
    {
        return $this->statut === 'disponible' && !$this->affectationOuverte()->exists();
    }

    public function getEstLoueAttribute(): bool
    {
        return $this->propriete === 'louee';
    }

    public function getAssuranceExpireeAttribute(): bool
    {
        $assurance = $this->documents()
            ->where('type', 'assurance')
            ->orderByDesc('date_expiration')
            ->first();

        return $assurance?->date_expiration?->isPast() ?? false;
    }

    public function getProchaineEcheanceDocumentAttribute()
    {
        return $this->documents()
            ->whereNotNull('date_expiration')
            ->where('date_expiration', '>=', now())
            ->orderBy('date_expiration')
            ->first()?->date_expiration;
    }

    public function getCoutTotalMaintenancesAttribute(): float
    {
        return (float) $this->maintenances()->sum('cout');
    }

    public function getTauxDisponibiliteAttribute(): float
    {
        $joursTotal = $this->date_achat ? $this->date_achat->diffInDays(now()) : 365;
        $joursPanne = (int) $this->pannes()->sum('heures_immobilisation') / 24;
        return $joursTotal > 0 ? round((($joursTotal - $joursPanne) / $joursTotal) * 100, 2) : 100;
    }

    // ==================== SCOPES ====================
    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeDisponibles($q) { return $q->where('statut', 'disponible'); }
    public function scopeEnService($q)   { return $q->where('statut', 'en_service'); }
    public function scopeEnPanne($q)     { return $q->where('statut', 'en_panne'); }
    public function scopePropres($q)     { return $q->where('propriete', 'propre'); }
    public function scopeLoues($q)       { return $q->where('propriete', 'louee'); }
    public function scopeSearch($q, string $term)
    {
        return $q->where(fn($qq) => $qq
            ->where('nom', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
            ->orWhere('numero_immatriculation', 'like', "%{$term}%")
        );
    }
}