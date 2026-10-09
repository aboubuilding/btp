<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\NonConformite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NonConformiteLevee
{
    use Dispatchable, SerializesModels;

    public function __construct(public NonConformite $nonConformite) {}
}