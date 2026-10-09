<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\ArticleDemandeAchat;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ArticleDemandeAchatPolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'responsable_materiel', 'responsable_achat');
    }

    public function update(User $user, ArticleDemandeAchat $article): bool
    {
        return $article->demande->statut === 'en_attente'
            && $this->create($user);
    }

    public function delete(User $user, ArticleDemandeAchat $article): bool
    {
        return $article->demande->statut === 'en_attente'
            && $user->hasRole('admin', 'direction');
    }
}