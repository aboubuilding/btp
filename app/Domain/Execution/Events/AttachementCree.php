<?php
namespace App\Domain\Execution\Events;

use App\Domain\Execution\Models\Attachement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachementCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public Attachement $attachement) {}

    public function toArray(): array
    {
        return [
            'attachement_id' => $this->attachement->id,
            'projet_id'      => $this->attachement->projet_id,
            'numero'         => $this->attachement->numero,
            'periode_debut'  => $this->attachement->periode_debut?->toDateString(),
            'periode_fin'    => $this->attachement->periode_fin?->toDateString(),
        ];
    }
}