<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\CongeApprouve;
use App\Domain\Socle\Models\User;
use App\Notifications\Personnel\CongeApprouveNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifierCongeApprouve implements ShouldQueue
{
    public string $queue = 'alertes';

    public function handle(CongeApprouve $event): void
    {
        $employeUser = User::whereHas('employe', fn($q) =>
            $q->where('id', $event->conge->employee_id)
        )->first();

        if ($employeUser) {
            $employeUser->notify(new CongeApprouveNotification($event->conge));
        }
    }
}