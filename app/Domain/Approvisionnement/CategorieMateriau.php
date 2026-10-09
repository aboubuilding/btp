<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieMateriau extends Model
{
    use HasFactory;

    protected $table = 'categorie_materiaux';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function materiaux() { return $this->hasMany(Materiau::class, 'categorie_id'); }

    public function scopeActif($q) { return $q->where('etat', 1); }
}