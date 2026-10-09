<?php
namespace App\Domain\Personnel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeConge extends Model
{
    use HasFactory;

    protected $table = 'type_conges';
    protected $guarded = ['id'];
    protected $casts = [
        'jours_par_defaut' => 'integer',
        'etat'             => 'integer',
    ];

    public function demandes() { return $this->hasMany(DemandeConge::class); }

    public function scopeActif($q) { return $q->where('etat', 1); }
}
