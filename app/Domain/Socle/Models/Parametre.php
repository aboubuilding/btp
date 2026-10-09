<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    protected $table = 'parametres';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    protected static function booted(): void
    {
        static::saved(fn($p) => Cache::forget("btp.param.{$p->cle}"));
        static::deleted(fn($p) => Cache::forget("btp.param.{$p->cle}"));
    }

    public static function get(string $cle, mixed $default = null): mixed
    {
        return app(\App\Domain\Socle\Services\ParametreService::class)->get($cle, $default);
    }

    public function getValeurTypeeAttribute(): mixed
    {
        return match ($this->type) {
            'decimal' => (float) $this->valeur,
            'integer' => (int) $this->valeur,
            'boolean' => filter_var($this->valeur, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($this->valeur, true),
            default   => $this->valeur,
        };
    }

    public function scopeActif($q)
    {
        return $q->where('etat', 1);
    }
}