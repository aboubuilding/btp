<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\EvaluationSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EvaluationSousTraitantCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public EvaluationSousTraitant $evaluation) {}
}