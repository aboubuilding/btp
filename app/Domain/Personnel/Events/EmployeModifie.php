<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\Employe;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeModifie
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Employe $employe,
        public array $changements = [],
    ) {}
}