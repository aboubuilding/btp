<?php
namespace App\Domain\Approvisionnement\Events;

use App\Domain\Approvisionnement\Models\Fournisseur;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FournisseurCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Fournisseur $fournisseur) {}
}