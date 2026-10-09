<?php
namespace App\Domain\Execution\Models;

use Illuminate\Database\Eloquent\Model;

class AvancementProjet extends Model
{
    protected $table = 'avancement_projets';
    protected $guarded = ['id'];
    protected $casts = [
        'date_rapport'           => 'date',
        'valide_le'              => 'datetime',
        'pourcentage_avancement' => 'integer',
        'etat'                   => 'integer',
    ];

    public function projet()    { return $this->belongsTo(Projet::class); }
    public function saisiPar()  { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'saisi_par'); }
    public function validePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'valide_par'); }

    public function documents()
    {
        return $this->morphMany(\App\Domain\Socle\Models\Document::class, 'documentable');
    }

    public function getEstValideAttribute(): bool
    {
        return !is_null($this->valide_le);
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeValides($q) { return $q->whereNotNull('valide_le'); }
    public function scopeEnAttente($q) { return $q->whereNull('valide_le'); }
    public function scopeRecents($q, int $jours = 30)
    {
        return $q->where('date_rapport', '>=', now()->subDays($jours));
    }
}