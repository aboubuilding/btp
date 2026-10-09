<?php
namespace App\Domain\Commercial\Models;

use Illuminate\Database\Eloquent\Model;

class AvenantMarche extends Model
{
    protected $table = 'avenant_marches';
    protected $guarded = ['id'];
    protected $casts = [
        'date_signature' => 'date',
        'montant'        => 'decimal:2',
        'est_signe'      => 'boolean',
        'etat'           => 'integer',
    ];

    public function marche() { return $this->belongsTo(Marche::class); }

    public function scopeSignes($q) { return $q->where('est_signe', true); }
    public function scopeActif($q)  { return $q->where('etat', 1); }
}