<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UtilisateurConnecte
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ip,
        public ?string $userAgent = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user->id,
            'email'      => $this->user->email,
            'ip'         => $this->ip,
            'user_agent' => $this->userAgent,
            'timestamp'  => now()->toIso8601String(),
        ];
    }
}