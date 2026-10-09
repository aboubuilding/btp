<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Attachement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachementValide
{
    use Dispatchable, SerializesModels;

    public function __construct(public Attachement $attachement) {}
}