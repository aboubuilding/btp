<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\DocumentEquipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentBientotExpire
{
    use Dispatchable, SerializesModels;

    public function __construct(public DocumentEquipement $document) {}

    public function toArray(): array
    {
        return [
            'document_id'    => $this->document->id,
            'equipement_id'  => $this->document->equipement_id,
            'type'           => $this->document->type,
            'numero'         => $this->document->numero,
            'date_expiration'=> $this->document->date_expiration?->toDateString(),
            'jours_restants' => (int) now()->diffInDays($this->document->date_expiration, false),
        ];
    }
}