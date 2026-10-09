<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\Reserve;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReserveLevee
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Reserve $reserve,
        public int $leveParId,
    ) {}
}