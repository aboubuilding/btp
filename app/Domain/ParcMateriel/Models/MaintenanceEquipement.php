<?php
namespace App\Domain\ParcMateriel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceEquipement extends Model
{
    use HasFactory;

    protected $table = 'maintenance_equipements';
    protected $guarded = ['id'];
    protected $casts = [
        'date_intervention'  => 'date',
        'prochaine_echeance' => 'date',
        'cout'               => 'decimal:2',
        'compteur'           => 'decimal:2',
    ];

    public function equipement() { return $this->belongsTo(Equipement::class); }

    public function getEstPreventiveAttribute(): bool { return $this->type === 'preventive'; }
    public function getEstCorrectiveAttribute(): bool { return $this->type === 'corrective'; }

    public function getEstDueAttribute(): bool
    {
        return $this->prochaine_echeance && $this->prochaine_echeance->isPast();
    }

    public function scopePreventives($q) { return $q->where('type', 'preventive'); }
    public function scopeCorrectives($q) { return $q->where('type', 'corrective'); }
    public function scopeEcheanceProche($q, int $jours = 30)
    {
        return $q->whereNotNull('prochaine_echeance')
            ->whereDate('prochaine_echeance', '<=', now()->addDays($jours));
    }
}