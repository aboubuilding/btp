<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $guarded = ['id'];
    protected $casts = [
        'type' => 'string',
        'etat' => 'integer',
    ];

    // ==================== RELATIONS ====================
    public function devis()   { return $this->hasMany(Devis::class); }
    public function marches() { return $this->hasMany(Marche::class); }
    public function projets() { return $this->hasMany(\App\Domain\Execution\Models\Projet::class); }

    public function factures()
    {
        return $this->morphMany(\App\Domain\Finances\Models\Facture::class, 'facturable');
    }

    // ==================== ACCESSORS ====================
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'particulier' => 'Particulier',
            'entreprise'  => 'Entreprise',
            'public'      => 'Organisme public',
            default       => ucfirst($this->type ?? '—'),
        };
    }

    public function getSoldeDuAttribute(): float
    {
        return (float) $this->factures()
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->sum('net_a_payer');
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)   { return $q->where('etat', 1); }
    public function scopeType($q, $type) { return $q->where('type', $type); }
    public function scopeSearch($q, string $term)
    {
        return $q->where('nom', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('nif', 'like', "%{$term}%");
    }
}