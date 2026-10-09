<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentEmploye extends Model
{
    protected $table = 'document_employes';
    protected $guarded = ['id'];
    protected $casts = [
        'date_expiration' => 'date',
    ];

    public function employe() { return $this->belongsTo(Employe::class, 'employee_id'); }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'diplome'              => 'Diplôme',
            'habilitation'         => 'Habilitation',
            'certificat_medical'   => 'Certificat médical',
            'autre'                => 'Autre',
            default                => ucfirst($this->type),
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

    public function scopeExpires($q)
    {
        return $q->whereNotNull('date_expiration')->whereDate('date_expiration', '<', now());
    }
}