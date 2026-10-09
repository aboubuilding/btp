<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $titre,
        public string $message,
        public string $url = '#',
        public string $couleur = 'info',
        public ?string $actionLabel = 'Voir dans BTP Manager',
        public ?array $meta = null,
    ) {}

    public function build(): self
    {
        return $this->subject($this->titre)
            ->view('emails.notification')
            ->with([
                'titre'       => $this->titre,
                'message'     => $this->message,
                'url'         => $this->url,
                'couleur'     => $this->couleur,
                'actionLabel' => $this->actionLabel,
                'meta'        => $this->meta,
            ]);
    }
}