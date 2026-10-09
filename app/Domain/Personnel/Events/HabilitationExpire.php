<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\DocumentEmploye;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HabilitationExpire
{
    use Dispatchable, SerializesModels;

    public function __construct(public DocumentEmploye $document) {}

    public function toArray(): array
    {
        return [
            'document_id'    => $this->document->id,
            'employe_id'     => $this->document->employee_id,
            'employe_nom'    => $this->document->employe?->nom_complet,
            'type'           => $this->document->type,
            'nom'            => $this->document->nom,
            'date_expiration'=> $this->document->date_expiration?->toDateString(),
        ];
    }
}