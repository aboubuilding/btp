<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class NotificationRepository
 *
 * @package App\Repositories\Eloquent
 */
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Notification::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle Notification
     *
     * Exemple :
     * public function findByEmail(string $email): ?Notification
     * {
     *     return $this->activeQuery()->where('email', $email)->first();
     * }
     */
}