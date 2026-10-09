<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\PvReception;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PvReceptionCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public PvReception $pv) {}
}