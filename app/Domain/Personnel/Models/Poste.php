<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;

    protected $table = 'postes';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function departement() { return $this->belongsTo(Departement::class); }
    public function employes()    { return $this->hasMany(Employe::class); }

    public function scopeActif($q) { return $q->where('etat', 1); }
}