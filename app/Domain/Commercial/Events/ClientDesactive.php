<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientDesactive
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Client $client,
        public ?string $raison = null,
    ) {}
}