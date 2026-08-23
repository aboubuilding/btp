<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'etat',
    ];

    protected $casts = [
        'etat' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isActive(): bool
    {
        return $this->etat === 1;
    }
}
