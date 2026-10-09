<?php
namespace App\Domain\Socle\Events;

use App\Domain\Socle\Models\Document;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentArchive
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Document $document,
        public string $ancienChemin,
        public string $nouveauChemin,
    ) {}
}