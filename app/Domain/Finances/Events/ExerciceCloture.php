<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\ExerciceFiscal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExerciceCloture
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ExerciceFiscal $exercice,
        public int $clotureParId,
    ) {}
}