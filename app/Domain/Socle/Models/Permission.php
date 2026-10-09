<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role',
            'permission_id',
            'role_id'
        );
    }

    public function scopeModule($q, string $module)
    {
        return $q->where('module', $module);
    }

    public function getLabelAttribute(): string
    {
        return $this->nom ?? $this->slug;
    }
}