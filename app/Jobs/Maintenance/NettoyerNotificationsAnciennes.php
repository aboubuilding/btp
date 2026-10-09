<?php
namespace App\Jobs\Maintenance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log};

class NettoyerNotificationsAnciennes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public string $queue = 'maintenance';

    public function __construct(public int $joursConservation = 90) {}

    public function handle(): void
    {
        $dateLimite = now()->subDays($this->joursConservation);

        $supprimes = DB::table('notifications')
            ->where('created_at', '<', $dateLimite)
            ->whereNotNull('read_at')
            ->delete();

        Log::info('[Job] Notifications nettoyées', [
            'jours'     => $this->joursConservation,
            'supprimes' => $supprimes,
        ]);
    }
}