<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceSalaire extends Model
{
    use HasFactory;

    protected $table = 'avance_salaires';
    protected $guarded = ['id'];
    protected $casts = [
        'date_avance'       => 'date',
        'montant'           => 'decimal:2',
        'montant_rembourse' => 'decimal:2',
        'approuve_le'       => 'datetime',
    ];

    public function employe()     { return $this->belongsTo(Employe::class, 'employee_id'); }
    public function approuvePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'approuve_par'); }

    public function getSoldeAttribute(): float
    {
        return (float) $this->montant - (float) $this->montant_rembourse;
    }

    public function getEstSoldeeAttribute(): bool
    {
        return $this->solde <= 0;
    }

    public function getTauxRemboursementAttribute(): float
    {
        return $this->montant > 0
            ? round(($this->montant_rembourse / $this->montant) * 100, 2)
            : 0;
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'demande'    => 'En attente',
            'approuvee'  => 'Approuvée',
            'remboursee' => 'Remboursée',
            'refusee'    => 'Refusée',
            default      => ucfirst($this->statut),
        };
    }

    public function scopeEnAttente($q) { return $q->where('statut', 'demande'); }
    public function scopeApprouvees($q) { return $q->where('statut', 'approuvee'); }
    public function scopeNonSoldees($q) { return $q->whereRaw('montant > montant_rembourse'); }
}