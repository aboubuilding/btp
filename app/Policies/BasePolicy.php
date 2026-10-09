<?php
namespace App\Policies;

use App\Domain\Socle\Models\User;
use App\Domain\Execution\Models\EquipeProjet;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    /**
     * Rôles transverses : accès total (bypass).
     */
    protected array $superAdmins = ['admin'];

    /**
     * Rôles de direction (voient tout).
     */
    protected array $direction = ['direction', 'directeur_technique'];

    /**
     * Méthode `before()` appelée avant chaque vérification.
     * Bypass total pour les super-admins.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole(...$this->superAdmins)) {
            return true;
        }
        return null;
    }

    // ============================================================
    // HELPERS DE RÔLE
    // ============================================================

    protected function estDirection(User $user): bool
    {
        return $user->hasRole(...$this->superAdmins, ...$this->direction);
    }

    protected function estRH(User $user): bool
    {
        return $user->hasRole('rh');
    }

    protected function estComptable(User $user): bool
    {
        return $user->hasRole('comptable');
    }

    protected function estChefChantier(User $user): bool
    {
        return $user->hasRole('chef_chantier');
    }

    protected function estConducteur(User $user): bool
    {
        return $user->hasRole('conducteur_travaux');
    }

    protected function estMagasinier(User $user): bool
    {
        return $user->hasRole('magasinier');
    }

    protected function estResponsableAchat(User $user): bool
    {
        return $user->hasRole('responsable_achat');
    }

    protected function estResponsableMateriel(User $user): bool
    {
        return $user->hasRole('responsable_materiel');
    }

    protected function estMetreur(User $user): bool
    {
        return $user->hasRole('metreur');
    }

    protected function estQhse(User $user): bool
    {
        return $user->hasRole('responsable_qhse');
    }

    // ============================================================
    // HELPERS DE PÉRIMÈTRE CHANTIER
    // ============================================================

    /**
     * L'utilisateur est-il le conducteur/chef du chantier ?
     */
    protected function estResponsableChantier(User $user, Model $projet): bool
    {
        $employeId = $user->employe?->id;
        if (!$employeId) return false;

        return $projet->conducteur_travaux_id === $employeId
            || $projet->chef_chantier_id === $employeId;
    }

    /**
     * L'utilisateur est-il membre de l'équipe du chantier ?
     */
    protected function estMembreChantier(User $user, Model $projet): bool
    {
        $employeId = $user->employe?->id;
        if (!$employeId) return false;

        return EquipeProjet::where('projet_id', $projet->id)
            ->where('employee_id', $employeId)
            ->exists();
    }

    /**
     * L'utilisateur a-t-il accès au chantier ?
     * - Direction : accès total
     * - Conducteur / Chef : ses chantiers
     * - Autres : s'ils sont affectés à l'équipe
     */
    protected function aAccesChantier(User $user, ?Model $projet): bool
    {
        if (!$projet) return false;
        if ($this->estDirection($user)) return true;
        if ($this->estResponsableChantier($user, $projet)) return true;
        return $this->estMembreChantier($user, $projet);
    }

    /**
     * L'utilisateur est-il l'employé lié au modèle ?
     */
    protected function estProprietaire(User $user, Model $model): bool
    {
        return $user->employe?->id === $model->employee_id
            || $user->id === $model->user_id;
    }
}