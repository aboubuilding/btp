<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieEquipement extends Model
{
    use HasFactory;

    protected $table = 'categorie_equipements';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function equipements() { return $this->hasMany(Equipement::class, 'categorie_id'); }

    public function scopeActif($q) { return $q->where('etat', 1); }
}