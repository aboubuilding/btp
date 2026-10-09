<?php
namespace App\Notifications\Socle;

use App\Notifications\BaseNotification;

class ExportDisponibleNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-file-export';
    protected string $priorite = 'normal';

    public function __construct(public string $filename)
    {
        $this->url = '#';
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '📊 Votre export est prêt';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'L\'export demandé est disponible : %s',
            basename($this->filename)
        );
    }

    public function toArray(object $notifiable): array
    {
        return array_merge(parent::toArray($notifiable), [
            'filename' => $this->filename,
        ]);
    }
}