<?php
namespace App\Domain\Socle\Services;

use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function envoyerAuxRoles(array $rolesSlugs, object $notification): void
    {
        $destinataires = \App\Domain\Socle\Models\User::whereHas(
            'role',
            fn($q) => $q->whereIn('slug', $rolesSlugs)
        )->where('est_actif', true)->get();

        if ($destinataires->isNotEmpty()) {
            Notification::send($destinataires, $notification);
        }
    }

    public function envoyerAUtilisateur(int $userId, object $notification): void
    {
        $user = \App\Domain\Socle\Models\User::find($userId);
        $user?->notify($notification);
    }

    public function envoyerParEmail(array $emails, object $notification): void
    {
        Notification::route('mail', $emails)->notify($notification);
    }

    public function nonLues(int $userId): int
    {
        return \DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', \App\Domain\Socle\Models\User::class)
            ->whereNull('read_at')
            ->count();
    }
}