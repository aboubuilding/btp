<?php
namespace App\Notifications;

use App\Domain\Socle\Models\User;
use Illuminate\Support\Facades\Notification;

/**
 * Helper pour dispatcher les notifications aux bons destinataires.
 */
class NotificationDispatcher
{
    /**
     * Envoie à une liste de rôles (par slug).
     */
    public static function auxRoles(array $rolesSlugs, object $notification): int
    {
        $destinataires = User::whereHas('role', fn($q) => $q->whereIn('slug', $rolesSlugs))
            ->where('est_actif', true)
            ->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, $notification);
        }

        return $destinataires->count();
    }

    /**
     * Envoie à un utilisateur précis.
     */
    public static function aUtilisateur(int $userId, object $notification): void
    {
        User::find($userId)?->notify($notification);
    }

    /**
     * Envoie par email sans compte utilisateur.
     */
    public static function parEmail(array $emails, object $notification): void
    {
        Notification::route('mail', $emails)->notify($notification);
    }

    /**
     * Envoie aux responsables d'un projet (conducteur + chef).
     */
    public static function auxResponsablesProjet(\App\Domain\Execution\Models\Projet $projet, object $notification): int
    {
        $employeIds = array_filter([
            $projet->conducteur_travaux_id,
            $projet->chef_chantier_id,
        ]);

        if (empty($employeIds)) return 0;

        $destinataires = User::whereHas('employe', fn($q) => $q->whereIn('id', $employeIds))
            ->where('est_actif', true)
            ->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, $notification);
        }

        return $destinataires->count();
    }
}