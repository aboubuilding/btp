<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\PeriodePaie;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PeriodePaieOuverte
{
    use Dispatchable, SerializesModels;

    public function __construct(public PeriodePaie $periode) {}
}