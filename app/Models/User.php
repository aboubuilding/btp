<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'nom',
        'email',
        'telephone',
        'avatar',
        'mot_de_passe',
        'est_actif',
        'derniere_connexion_le',
        'etat',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'email_verifie_le' => 'datetime',
        'derniere_connexion_le' => 'datetime',
        'est_actif' => 'boolean',
        'etat' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isActive(): bool
    {
        return $this->est_actif && $this->etat === 1;
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    public function hasRole(string $slug): bool
    {
        return $this->role && $this->role->slug === $slug;
    }

    public function getFullNameAttribute(): string
    {
        return $this->nom;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->etat === 2) {
            return 'Supprimé';
        }
        if (!$this->est_actif) {
            return 'Inactif';
        }
        return 'Actif';
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->etat === 2) {
            return 'danger';
        }
        if (!$this->est_actif) {
            return 'warning';
        }
        return 'success';
    }
}
