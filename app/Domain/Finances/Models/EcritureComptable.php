<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcritureComptable extends Model
{
    use HasFactory;

    protected $table = 'ecriture_comptables';
    protected $guarded = ['id'];
    protected $casts = [
        'date_ecriture' => 'date',
        'valide_le'     => 'datetime',
    ];

    public function exercice() { return $this->belongsTo(ExerciceFiscal::class, 'exercice_fiscal_id'); }
    public function lignes()   { return $this->hasMany(LigneEcritureComptable::class); }
    public function validePar(){ return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function getTotalDebitAttribute(): float
    {
        return (float) $this->lignes()->sum('debit');
    }

    public function getTotalCreditAttribute(): float
    {
        return (float) $this->lignes()->sum('credit');
    }

    public function getEstEquilibreeAttribute(): bool
    {
        return round($this->total_debit, 2) === round($this->total_credit, 2);
    }

    public function getEstValideeAttribute(): bool { return $this->statut === 'validee'; }
    public function getEstModifiableAttribute(): bool { return $this->statut !== 'validee'; }

    public function scopeValidees($q) { return $q->where('statut', 'validee'); }
    public function scopeBrouillons($q) { return $q->where('statut', 'brouillon'); }
}