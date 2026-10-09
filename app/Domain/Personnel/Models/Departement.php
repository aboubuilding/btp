<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departements';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function postes()   { return $this->hasMany(Poste::class); }
    public function employes() { return $this->hasMany(Employe::class); }

    public function getNbEmployesAttribute(): int
    {
        return $this->employes()->where('etat', 1)->count();
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
}