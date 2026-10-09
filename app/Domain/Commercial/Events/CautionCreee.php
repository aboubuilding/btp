<?php
namespace App\Domain\Commercial\Events;

use App\Domain\Commercial\Models\CautionMarche;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CautionCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public CautionMarche $caution) {}
}