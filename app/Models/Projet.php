<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $table = 'projets';

    protected $fillable = [
        'code',
        'nom',
        'client_id',
        'adresse',
        'ville',
        'description',
        'type',
        'conducteur_travaux_id',
        'chef_chantier_id',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'budget_prevue',
        'budget_reel',
        'montant_contrat',
        'pourcentage_avancement',
        'statut',
        'etat',
    ];

    protected $casts = [
        'budget_prevue' => 'decimal:2',
        'budget_reel' => 'decimal:2',
        'montant_contrat' => 'decimal:2',
        'pourcentage_avancement' => 'integer',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'statut' => 'planifie',
        'etat' => 1,
    ];

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contratsSousTraitants()
    {
        return $this->hasMany(ContratSousTraitant::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->code . ' - ' . $this->nom;
    }
}
