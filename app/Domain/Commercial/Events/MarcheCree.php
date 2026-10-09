<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\Marche;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarcheCree
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Marche $marche,
        public ?int $creeParId = null,
    ) {}
}