<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleBonCommande extends Model
{
    use HasFactory;

    protected $table = 'article_bon_commands';
    protected $guarded = ['id'];
    protected $casts = [
        'quantite'     => 'decimal:3',
        'prix_unitaire'=> 'decimal:2',
        'montant'      => 'decimal:2',
    ];

    public function bonCommande() { return $this->belongsTo(BonCommande::class); }
    public function materiau()    { return $this->belongsTo(Materiau::class); }

    public function getQuantiteRecueAttribute(): float
    {
        return (float) ArticleLivraison::where('materiau_id', $this->materiau_id)
            ->whereHas('livraison', fn($q) => $q->where('bon_commande_id', $this->bon_commande_id))
            ->sum('quantite_recue');
    }

    public function getResteALivrerAttribute(): float
    {
        return max(0, (float) $this->quantite - $this->quantite_recue);
    }

    public function getEstCompletementLivreAttribute(): bool
    {
        return $this->quantite_recue >= (float) $this->quantite;
    }
}