<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Depense;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepenseApprouvee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Depense $depense,
        public int $approuveParId,
    ) {}
}