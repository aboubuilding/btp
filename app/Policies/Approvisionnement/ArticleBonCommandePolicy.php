<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\ArticleBonCommande;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ArticleBonCommandePolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function update(User $user, ArticleBonCommande $article): bool
    {
        return !$article->bonCommande->est_fige
            && $this->create($user);
    }

    public function delete(User $user, ArticleBonCommande $article): bool
    {
        return !$article->bonCommande->est_fige
            && $user->hasRole('admin', 'responsable_achat');
    }
}