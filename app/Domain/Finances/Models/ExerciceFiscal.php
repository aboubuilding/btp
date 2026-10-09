<?php
namespace App\Domain\Finances\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciceFiscal extends Model
{
    use HasFactory;

    protected $table = 'exercice_fiscals';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    public function ecritures() { return $this->hasMany(EcritureComptable::class); }

    public function getEstOuvertAttribute(): bool   { return $this->statut === 'ouvert'; }
    public function getEstClotureAttribute(): bool  { return $this->statut === 'cloture'; }

    public function getStatutLabelAttribute(): string
    {
        return $this->statut === 'ouvert' ? 'Ouvert' : 'Clôturé';
    }

    public function scopeOuverts($q) { return $q->where('statut', 'ouvert'); }
    public function scopeActif($q)   { return $q->where('statut', 'ouvert')->latest('date_debut'); }
}