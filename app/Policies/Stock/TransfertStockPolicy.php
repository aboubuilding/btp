<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\TransfertStock;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class TransfertStockPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier');
    }

    public function view(User $user, TransfertStock $transfert): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'magasinier', 'responsable_achat');
    }

    public function approuver(User $user, TransfertStock $transfert): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique')
            && $transfert->statut === 'en_attente';
    }

    public function rejeter(User $user, TransfertStock $transfert): bool
    {
        return $this->approuver($user, $transfert);
    }

    public function delete(User $user, TransfertStock $transfert): bool
    {
        if ($transfert->statut !== 'en_attente') return false;
        return $user->hasRole('admin', 'direction', 'responsable_achat');
    }
}