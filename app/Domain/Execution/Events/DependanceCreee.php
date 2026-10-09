<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\DependanceTache;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DependanceCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public DependanceTache $dependance) {}
}