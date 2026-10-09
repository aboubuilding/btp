<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['mot_de_passe', 'remember_token'];

    protected $casts = [
        'email_verifie_le'       => 'datetime',
        'derniere_connexion_le'  => 'datetime',
        'est_actif'              => 'boolean',
        'etat'                   => 'integer',
    ];

    // ==================== AUTH ====================
    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    // ==================== RELATIONS ====================
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function employe()
    {
        return $this->hasOne(\App\Domain\Personnel\Models\Employe::class, 'user_id');
    }

    public function journalActivites()
    {
        return $this->hasMany(JournalActivite::class, 'user_id');
    }

    public function communicationsEnvoyees()
    {
        return $this->hasMany(Communication::class, 'expediteur_id');
    }

    // ==================== HELPERS ====================
    public function hasRole(string ...$slugs): bool
    {
        return in_array($this->role?->slug, $slugs, true);
    }

    public function hasAnyRole(array $slugs): bool
    {
        return in_array($this->role?->slug, $slugs, true);
    }

    public function hasPermission(string $slug): bool
    {
        return $this->role?->permissions()
            ->where('slug', $slug)
            ->exists() ?? false;
    }

    public function getNomCompletAttribute(): string
    {
        return $this->nom ?? '';
    }

    public function getInitialesAttribute(): string
    {
        return strtoupper(substr($this->nom ?? 'XX', 0, 2));
    }

    // ==================== SCOPES ====================
    public function scopeActif($q)
    {
        return $q->where('est_actif', true)->where('etat', 1);
    }

    public function scopeRole($q, string $slug)
    {
        return $q->whereHas('role', fn($qq) => $qq->where('slug', $slug));
    }
}