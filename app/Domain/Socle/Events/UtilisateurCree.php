<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UtilisateurCree
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $creeParNom = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user->id,
            'nom'        => $this->user->nom,
            'email'      => $this->user->email,
            'role'       => $this->user->role?->slug,
            'cree_par'   => $this->creeParNom,
            'created_at' => now()->toIso8601String(),
        ];
    }
}