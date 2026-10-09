<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLivraison extends Model
{
    protected $table = 'article_livraisons';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite_commandee' => 'decimal:3',
        'quantite_recue'     => 'decimal:3',
    ];

    public function livraison() { return $this->belongsTo(Livraison::class); }
    public function materiau()  { return $this->belongsTo(Materiau::class); }

    public function getEstConformeAttribute(): bool
    {
        return $this->etat_article === 'bon_etat';
    }

    public function getEcartAttribute(): float
    {
        return (float) $this->quantite_recue - (float) $this->quantite_commandee;
    }
}