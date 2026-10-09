<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\Communication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunicationEchouee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Communication $communication,
        public string $erreur,
    ) {}
}