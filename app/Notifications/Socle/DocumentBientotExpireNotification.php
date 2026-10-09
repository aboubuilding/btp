<?php
namespace App\Notifications\Socle;

use App\Notifications\BaseNotification;

class DocumentBientotExpireNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-file-circle-exclamation';
    protected string $priorite = 'high';

    public function __construct(
        public mixed $document,
    ) {
        $this->url = $this->determineUrl();
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '📅 Document bientôt expiré';
    }

    protected function getMessage(): string
    {
        $type = $this->getTypeLabel();
        $jours = max(0, (int) now()->diffInDays($this->document->date_expiration, false));

        return sprintf(
            '%s arrive à expiration dans %d jour(s) — le %s.',
            $type,
            $jours,
            $this->formatDate($this->document->date_expiration)
        );
    }

    private function getTypeLabel(): string
    {
        if (method_exists($this->document, 'getTypeLabelAttribute')) {
            return $this->document->type_label;
        }
        if (isset($this->document->type)) {
            return ucfirst(str_replace('_', ' ', $this->document->type));
        }
        return 'Document';
    }

    private function determineUrl(): string
    {
        if ($this->document instanceof \App\Domain\ParcMateriel\Models\DocumentEquipement) {
            return route('materiel.equipements.show', $this->document->equipement_id);
        }
        if ($this->document instanceof \App\Domain\Personnel\Models\DocumentEmploye) {
            return route('rh.employes.show', $this->document->employee_id);
        }
        return '#';
    }
}