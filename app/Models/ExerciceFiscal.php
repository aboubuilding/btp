<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciceFiscal extends Model
{
    use HasFactory;

    protected $table = 'exercices_fiscaux';

    protected $fillable = [
        'nom',
        'date_debut',
        'date_fin',
        'statut',
        'etat',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'etat' => 'integer',
    ];

    protected $attributes = [
        'statut' => 'ouvert',
        'etat' => 1,
    ];

    public function ecritures()
    {
        return $this->hasMany(EcritureComptable::class);
    }

    public function getStatutBadgeAttribute(): string
    {
        return $this->statut === 'ouvert' ? 'success' : 'danger';
    }

    public function getStatutLabelAttribute(): string
    {
        return $this->statut === 'ouvert' ? 'Ouvert' : 'Clôturé';
    }

    public function isOpen(): bool
    {
        return $this->statut === 'ouvert' && $this->etat === 1;
    }

    public function isClosed(): bool
    {
        return $this->statut === 'cloture';
    }
}
