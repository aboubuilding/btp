<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\Contrat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContratCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Contrat $contrat) {}
}