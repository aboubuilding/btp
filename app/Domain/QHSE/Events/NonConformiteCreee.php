<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\NonConformite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NonConformiteCreee
{
    use Dispatchable, SerializesModels;

    public function __construct(public NonConformite $nonConformite) {}

    public function toArray(): array
    {
        return [
            'nc_id'       => $this->nonConformite->id,
            'projet_id'   => $this->nonConformite->projet_id,
            'ouvrage'     => $this->nonConformite->ouvrage,
            'date_constat'=> $this->nonConformite->date_constat?->toDateString(),
        ];
    }
}