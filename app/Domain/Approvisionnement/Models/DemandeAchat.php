<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeAchat extends Model
{
    use HasFactory;

    protected $table = 'demande_achats';
    protected $guarded = ['id'];
    protected $casts = [
        'date_demande' => 'date',
        'valide_le'    => 'datetime',
        'etat'         => 'integer',
    ];

    public function projet()    { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function demandeur() { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'demandeur_id'); }
    public function validePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }
    public function articles()  { return $this->hasMany(ArticleDemandeAchat::class); }
    public function bonsCommande() { return $this->hasMany(BonCommande::class); }

    public function getEstEnAttenteAttribute(): bool { return $this->statut === 'en_attente'; }
    public function getEstValideeAttribute(): bool   { return $this->statut === 'validee'; }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeStatut($q, $statut) { return $q->where('statut', $statut); }
    public function scopeEnAttente($q) { return $q->where('statut', 'en_attente'); }
    public function scopeValidees($q) { return $q->where('statut', 'validee'); }
}