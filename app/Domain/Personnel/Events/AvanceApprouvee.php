<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\AvanceSalaire;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AvanceApprouvee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public AvanceSalaire $avance,
        public int $approuveParId,
    ) {}
}