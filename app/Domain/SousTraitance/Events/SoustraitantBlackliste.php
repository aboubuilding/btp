<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\Soustraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SoustraitantBlackliste
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Soustraitant $soustraitant,
        public string $motif,
    ) {}
}