<?php
namespace App\Domain\Approvisionnement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';
    protected $guarded = ['id'];
    protected $casts = [
        'note_evaluation' => 'decimal:1',
        'etat'            => 'integer',
    ];

    public function bonsCommande()  { return $this->hasMany(BonCommande::class); }
    public function equipementsLoues() { return $this->hasMany(\App\Domain\ParcMateriel\Models\Equipement::class, 'fournisseur_loueur_id'); }

    public function factures()
    {
        return $this->morphMany(\App\Domain\Finances\Models\Facture::class, 'facturable');
    }

    public function getCategorieLabelAttribute(): string
    {
        return match ($this->categorie) {
            'materiaux'         => 'Matériaux',
            'carburant'         => 'Carburant',
            'pieces_detachees'  => 'Pièces détachées',
            'location_engins'   => 'Location d\'engins',
            'services'          => 'Services',
            'divers'            => 'Divers',
            default             => ucfirst($this->categorie ?? '—'),
        };
    }

    public function scopeActif($q) { return $q->where('etat', 1); }
    public function scopeCategorie($q, $cat) { return $q->where('categorie', $cat); }
}