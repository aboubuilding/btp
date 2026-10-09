<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleInventaire extends Model
{
    protected $table = 'article_inventaires';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite_theorique' => 'decimal:3',
        'quantite_comptee'   => 'decimal:3',
        'ecart'              => 'decimal:3',
    ];

    public function inventaire() { return $this->belongsTo(Inventaire::class); }
    public function materiau()   { return $this->belongsTo(Materiau::class); }

    public function getEstConformeAttribute(): bool
    {
        return abs((float) $this->ecart) < 0.01;
    }
}