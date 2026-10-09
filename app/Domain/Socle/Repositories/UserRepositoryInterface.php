<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function parRole(string $slug): Collection;
    public function pourDirection(): Collection;
    public function actifs(): Collection;
    public function paginateAvecRole(array $filtres = [], int $parPage = 25): LengthAwarePaginator;
    public function creerAvecRole(array $data, string $motDePasse): User;
    public function mettreAJourMotDePasse(User $user, string $motDePasse): bool;
    public function enregistrerConnexion(User $user, string $ip): void;
}