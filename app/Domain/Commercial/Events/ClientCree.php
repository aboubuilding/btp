<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientCree
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Client $client,
        public ?int $creeParId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'client_id' => $this->client->id,
            'nom'       => $this->client->nom,
            'type'      => $this->client->type,
            'cree_par'  => $this->creeParId,
        ];
    }
}