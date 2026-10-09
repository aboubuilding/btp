<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePaie extends Model
{
    use HasFactory;

    protected $table = 'periode_paies';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut'  => 'date',
        'date_fin'    => 'date',
        'cloture_le'  => 'datetime',
    ];

    public function bulletins()  { return $this->hasMany(BulletinPaie::class, 'periode_paie_id'); }
    public function cloturePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'cloture_par'); }

    public function getEstOuverteAttribute(): bool  { return $this->statut === 'ouverte'; }
    public function getEstClotureeAttribute(): bool { return $this->statut === 'cloturee'; }
    public function getEstPayeeAttribute(): bool    { return $this->statut === 'payee'; }

    public function getTotalBrutAttribute(): float
    {
        return (float) $this->bulletins()->sum('brut');
    }

    public function getTotalNetAttribute(): float
    {
        return (float) $this->bulletins()->sum('net_a_payer');
    }

    public function getTotalCotisationsAttribute(): float
    {
        return (float) $this->bulletins()->sum('cotisations_salariales')
             + (float) $this->bulletins()->sum('cotisations_patronales');
    }

    public function getNbBulletinsAttribute(): int
    {
        return $this->bulletins()->count();
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'ouverte'  => 'Ouverte',
            'cloturee' => 'Clôturée',
            'payee'    => 'Payée',
            default    => ucfirst($this->statut),
        };
    }

    public function scopeOuvertes($q) { return $q->where('statut', 'ouverte'); }
    public function scopeCloturees($q) { return $q->where('statut', 'cloturee'); }
}