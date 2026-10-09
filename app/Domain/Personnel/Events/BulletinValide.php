<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\BulletinPaie;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BulletinValide
{
    use Dispatchable, SerializesModels;

    public function __construct(public BulletinPaie $bulletin) {}
}