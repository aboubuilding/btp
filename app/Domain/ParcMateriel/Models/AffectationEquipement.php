<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationEquipement extends Model
{
    use HasFactory;

    protected $table = 'affectation_equipements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'     => 'date',
        'date_fin'       => 'date',
        'compteur_debut' => 'decimal:2',
        'compteur_fin'   => 'decimal:2',
        'cout_impute'    => 'decimal:2',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }
    public function projet()     { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function validePar()  { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function getEstOuverteAttribute(): bool { return $this->statut === 'ouverte'; }
    public function getEstFermeeAttribute(): bool  { return $this->statut === 'fermee'; }

    public function getDureeJoursAttribute(): ?int
    {
        if (!$this->date_fin) return null;
        return $this->date_debut->diffInDays($this->date_fin);
    }

    public function getHeuresUtiliseesAttribute(): ?float
    {
        return $this->compteur_fin && $this->compteur_debut
            ? round((float) $this->compteur_fin - (float) $this->compteur_debut, 2)
            : null;
    }

    public function getCoutCalculeAttribute(): float
    {
        return $this->heures_utilisees && $this->equipement
            ? round($this->heures_utilisees * (float) $this->equipement->cout_horaire, 2)
            : 0;
    }

    public function scopeOuvertes($q) { return $q->where('statut', 'ouverte'); }
    public function scopeFermees($q)  { return $q->where('statut', 'fermee'); }
}