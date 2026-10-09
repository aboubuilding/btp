<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Communication extends Model
{
    protected $table = 'communications';
    protected $guarded = ['id'];

    protected $casts = [
        'destinataires'  => 'array',
        'pieces_jointes' => 'array',
        'envoye_le'      => 'datetime',
        'planifie_le'    => 'datetime',
        'etat'           => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function communicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    // ==================== ACCESSORS ====================
    public function getEstEnvoyeAttribute(): bool
    {
        return $this->statut === 'envoye';
    }

    public function getNbDestinatairesAttribute(): int
    {
        return count($this->destinataires ?? []);
    }

    public function getEmailsDestinatairesAttribute(): array
    {
        return collect($this->destinataires ?? [])->pluck('email')->filter()->all();
    }

    // ==================== SCOPES ====================
    public function scopeEnvoyes($q)    { return $q->where('statut', 'envoye'); }
    public function scopeBrouillons($q) { return $q->where('statut', 'brouillon'); }
    public function scopeEchoues($q)    { return $q->where('statut', 'echoue'); }
    public function scopePlanifies($q)
    {
        return $q->where('statut', 'brouillon')->whereNotNull('planifie_le');
    }
    public function scopeAPlanifier($q)
    {
        return $q->planifies()->where('planifie_le', '<=', now());
    }
}