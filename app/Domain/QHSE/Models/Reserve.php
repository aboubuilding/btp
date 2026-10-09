<?php
namespace App\Domain\QHSE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reserve extends Model
{
    use HasFactory;

    protected $table = 'reserves';
    protected $guarded = ['id'];
    protected $casts = [
        'date_limite' => 'date',
        'date_levee'  => 'date',
        'etat'        => 'integer',
    ];

    public function pv() { return $this->belongsTo(PvReception::class, 'pv_reception_id'); }

    public function responsable(): MorphTo
    {
        return $this->morphTo('responsable', 'type_responsable', 'responsable_id');
    }

    public function getEstOuverteAttribute(): bool { return $this->statut === 'ouverte'; }
    public function getEstLeveeAttribute(): bool   { return $this->statut === 'levee'; }

    public function getStatutLabelAttribute(): string
    {
        return $this->statut === 'levee' ? 'Levée' : 'Ouverte';
    }

    public function getCouleurStatutAttribute(): string
    {
        return $this->statut === 'levee' ? 'success' : 'danger';
    }

    public function getEstEnRetardAttribute(): bool
    {
        return !$this->date_levee
            && $this->date_limite
            && $this->date_limite->isPast();
    }

    public function getJoursRestantsAttribute(): ?int
    {
        return $this->date_limite && !$this->date_levee
            ? (int) now()->diffInDays($this->date_limite, false)
            : null;
    }

    public function getEstResponsableEmployeAttribute(): bool
    {
        return $this->type_responsable === 'Employe';
    }

    public function getEstResponsableSousTraitantAttribute(): bool
    {
        return $this->type_responsable === 'Soustraitant';
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeOuvertes($q) { return $q->where('statut', 'ouverte'); }
    public function scopeLevees($q) { return $q->where('statut', 'levee'); }
    public function scopeEnRetard($q)
    {
        return $q->where('statut', 'ouverte')
            ->whereNotNull('date_limite')
            ->whereDate('date_limite', '<', now());
    }
}