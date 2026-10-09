<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Model;

class EquipeProjet extends Model
{
    protected $table = 'equipe_projets';
    protected $guarded = ['id'];
    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'etat'       => 'integer',
    ];

    public function projet()  { return $this->belongsTo(Projet::class); }
    public function employe() { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'employee_id'); }

    public function getEstActiveAttribute(): bool
    {
        return $this->etat === 1
            && (!$this->date_fin || $this->date_fin->isFuture());
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeActives($q)
    {
        return $q->where('etat', 1)
            ->where(fn($qq) => $qq->whereNull('date_fin')->orWhere('date_fin', '>=', now()));
    }
}