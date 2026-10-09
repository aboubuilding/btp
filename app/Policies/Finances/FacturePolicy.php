<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\Facture;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class FacturePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'conducteur_travaux');
    }

    public function view(User $user, Facture $facture): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'directeur_technique');
    }

    public function update(User $user, Facture $facture): bool
    {
        if (in_array($facture->statut, ['payee', 'annulee'], true)) return false;
        return $user->hasRole('admin', 'comptable', 'directeur_technique');
    }

    public function valider(User $user, Facture $facture): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function annuler(User $user, Facture $facture): bool
    {
        if ($facture->statut === 'payee') return false;
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function pdf(User $user, Facture $facture): bool
    {
        return $this->view($user, $facture);
    }

    public function delete(User $user, Facture $facture): bool
    {
        if ($facture->statut !== 'emise') return false;
        return $user->hasRole('admin', 'direction');
    }

    public function envoyerParEmail(User $user, Facture $facture): bool
    {
        return $this->view($user, $facture);
    }
}