<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleDemandeAchat extends Model
{
    protected $table = 'article_demande_achats';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite' => 'decimal:3',
    ];

    public function demande()  { return $this->belongsTo(DemandeAchat::class, 'demande_achat_id'); }
    public function materiau() { return $this->belongsTo(Materiau::class); }
}