<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentEquipement extends Model
{
    protected $table = 'document_equipements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_emission'   => 'date',
        'date_expiration' => 'date',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'assurance'        => 'Assurance',
            'visite_technique' => 'Visite technique',
            'carte_grise'      => 'Carte grise',
            'autre'            => 'Autre',
            default            => ucfirst($this->type),
        };
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->date_expiration?->isPast() ?? false;
    }

    public function getExpireBientotAttribute(): bool
    {
        return $this->date_expiration
            && !$this->date_expiration->isPast()
            && now()->diffInDays($this->date_expiration) <= 30;
    }

    public function getJoursRestantsAttribute(): ?int
    {
        return $this->date_expiration
            ? (int) now()->diffInDays($this->date_expiration, false)
            : null;
    }

    public function scopeExpires($q)
    {
        return $q->whereNotNull('date_expiration')->whereDate('date_expiration', '<', now());
    }

    public function scopeExpirentBientot($q, int $jours = 30)
    {
        return $q->whereNotNull('date_expiration')
            ->whereDate('date_expiration', '>=', now())
            ->whereDate('date_expiration', '<=', now()->addDays($jours));
    }
}