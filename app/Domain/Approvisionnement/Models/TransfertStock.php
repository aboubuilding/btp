<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Model;

class TransfertStock extends Model
{
    protected $table = 'transfert_stocks';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite'    => 'decimal:3',
        'approuve_le' => 'datetime',
    ];

    public function entrepotSource()      { return $this->belongsTo(Entrepot::class, 'entrepot_source_id'); }
    public function entrepotDestination() { return $this->belongsTo(Entrepot::class, 'entrepot_destination_id'); }
    public function materiau()            { return $this->belongsTo(Materiau::class); }
    public function demandePar()          { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'demande_par'); }
    public function approuvePar()         { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'approuve_par'); }

    public function getEstEnAttenteAttribute(): bool { return $this->statut === 'en_attente'; }
    public function getEstApprouveAttribute(): bool  { return $this->statut === 'approuve'; }
    public function getEstRejeteAttribute(): bool    { return $this->statut === 'rejete'; }

    public function scopeEnAttente($q) { return $q->where('statut', 'en_attente'); }
    public function scopeApprouves($q) { return $q->where('statut', 'approuve'); }
}