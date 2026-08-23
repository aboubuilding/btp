<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function getUsersWithRole(): array;
    public function updateLastLogin(int $id): bool;
    public function resetPassword(int $id, string $password): bool;
    public function toggleActive(int $id): bool;
    public function assignRole(int $userId, int $roleId): bool;
    public function search(string $keyword): array;
}
