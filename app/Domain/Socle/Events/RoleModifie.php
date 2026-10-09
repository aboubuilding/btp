<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleModifie
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Role $role,
        public array $changements = [],
    ) {}
}