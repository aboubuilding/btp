<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Model;

class DependanceTache extends Model
{
    protected $table = 'dependance_taches';
    protected $guarded = ['id'];

    public function tache()      { return $this->belongsTo(Tache::class, 'tache_id'); }
    public function dependDe()   { return $this->belongsTo(Tache::class, 'depend_de_tache_id'); }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'fin_debut'   => 'Fin → Début',
            'debut_debut' => 'Début → Début',
            'fin_fin'     => 'Fin → Fin',
            'debut_fin'   => 'Début → Fin',
            default       => ucfirst($this->type),
        };
    }
}