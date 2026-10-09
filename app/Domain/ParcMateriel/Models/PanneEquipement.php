<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanneEquipement extends Model
{
    use HasFactory;

    protected $table = 'panne_equipements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_panne'            => 'date',
        'date_cloture'          => 'date',
        'cout_reparation'       => 'decimal:2',
        'heures_immobilisation' => 'integer',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }
    public function declarePar() { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'declare_par'); }

    public function getEstClotureeAttribute(): bool { return $this->statut === 'cloturee'; }
    public function getEstEnCoursAttribute(): bool  { return in_array($this->statut, ['declaree', 'en_reparation']); }

    public function getDureeImmobilisationAttribute(): int
    {
        return $this->date_cloture
            ? (int) $this->date_panne->diffInHours($this->date_cloture)
            : (int) now()->diffInHours($this->date_panne);
    }

    public function scopeEnCours($q) { return $q->whereIn('statut', ['declaree', 'en_reparation']); }
    public function scopeCloturees($q) { return $q->where('statut', 'cloturee'); }
}