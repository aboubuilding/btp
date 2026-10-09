<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    protected $table = 'journal_activites';
    protected $guarded = ['id'];

    protected $casts = [
        'meta'        => 'array',
        'date_action' => 'datetime',
    ];

    // ==================== RELATIONS ====================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function objet()
    {
        return $this->morphTo('objet', 'objet_type', 'objet_id');
    }

    // ==================== ACCESSORS ====================
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create'   => 'Création',
            'update'   => 'Modification',
            'delete'   => 'Suppression',
            'validate' => 'Validation',
            default    => ucfirst($this->action),
        };
    }

    public function getObjetNomAttribute(): string
    {
        return $this->objet_type ? class_basename($this->objet_type) : '—';
    }

    // ==================== SCOPES ====================
    public function scopeAction($q, string $action)
    {
        return $q->where('action', $action);
    }

    public function scopeRecents($q, int $jours = 30)
    {
        return $q->where('date_action', '>=', now()->subDays($jours));
    }
}