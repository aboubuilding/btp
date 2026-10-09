<?php
namespace App\Domain\QHSE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauserieSecurite extends Model
{
    use HasFactory;

    protected $table = 'causerie_securites';
    protected $guarded = ['id'];
    protected $casts = [
        'date'                => 'date',
        'nombre_participants' => 'integer',
        'etat'                => 'integer',
    ];

    public function projet()    { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function animateur() { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'anime_par'); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeDuProjet($q, int $projetId) { return $q->where('projet_id', $projetId); }
    public function scopeRecentes($q, int $jours = 30) { return $q->where('date', '>=', now()->subDays($jours)); }
}