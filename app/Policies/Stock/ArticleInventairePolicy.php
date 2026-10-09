<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\ArticleInventaire;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ArticleInventairePolicy extends BasePolicy
{
    public function update(User $user, ArticleInventaire $article): bool
    {
        return $article->inventaire->statut !== 'valide'
            && $user->hasRole('admin', 'responsable_achat', 'magasinier');
    }
}