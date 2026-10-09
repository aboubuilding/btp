<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $table = 'livraisons';
    protected $guarded = ['id'];
    protected $casts = [
        'date_livraison' => 'date',
        'etat'           => 'integer',
    ];

    public function bonCommande()    { return $this->belongsTo(BonCommande::class); }
    public function entrepot()        { return $this->belongsTo(Entrepot::class); }
    public function receptionnaire()  { return $this->belongsTo(\App\Domain\Personnel\Models\Employe::class, 'receptionnaire_id'); }
    public function articles()        { return $this->hasMany(ArticleLivraison::class); }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'partielle' => 'Partielle',
            'complete'  => 'Complète',
            'refusee'   => 'Refusée',
            default     => ucfirst($this->statut),
        };
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeCompletes($q) { return $q->where('statut', 'complete'); }
}