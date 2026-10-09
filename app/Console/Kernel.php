<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // ================================================================
        // ALERTES QUOTIDIENNES (07h00 - 07h45)
        // ================================================================
        $schedule->job(new \App\Jobs\Alertes\VerifierDocumentsExpires())
            ->dailyAt('07:00')
            ->name('alerte.documents')
            ->withoutOverlapping()
            ->onOneServer();

        $schedule->job(new \App\Jobs\Alertes\VerifierStockSousSeuil())
            ->dailyAt('07:05')
            ->name('alerte.stock')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierTachesEnRetard())
            ->dailyAt('07:10')
            ->name('alerte.taches_retard')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierFacturesEchues())
            ->dailyAt('07:15')
            ->name('alerte.factures_echues')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierContratsEcheance())
            ->dailyAt('07:20')
            ->name('alerte.contrats')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierJalonsManques())
            ->dailyAt('07:25')
            ->name('alerte.jalons')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierMaintenancesDues())
            ->dailyAt('07:30')
            ->name('alerte.maintenances')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierCautionsExpirantes())
            ->dailyAt('07:35')
            ->name('alerte.cautions')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Alertes\VerifierHabiliationsEmployes())
            ->weeklyOn(1, '07:40') // Lundi matin
            ->name('alerte.habilitations');

        // ================================================================
        // COMMUNICATION (toutes les 5 minutes)
        // ================================================================
        $schedule->job(new \App\Jobs\Communication\EnvoyerCommunicationsPlanifiees())
            ->everyFiveMinutes()
            ->name('comm.planifiees')
            ->withoutOverlapping();

        $schedule->job(new \App\Jobs\Communication\RelancerFacturesImpayees())
            ->weeklyOn(2, '08:00') // Mardi matin
            ->name('comm.relances_factures');

        // ================================================================
        // RAPPORTS
        // ================================================================
        $schedule->job(new \App\Jobs\Rapports\GenererRapportJournalier())
            ->dailyAt('18:00')
            ->name('rapport.journalier')
            ->weekdays();

        $schedule->job(new \App\Jobs\Rapports\GenererRapportHebdomadaire())
            ->weeklyOn(1, '08:00') // Lundi matin
            ->name('rapport.hebdo');

        $schedule->job(new \App\Jobs\Rapports\GenererRapportMensuel())
            ->monthlyOn(1, '08:00')
            ->name('rapport.mensuel');

        // ================================================================
        // COMPTABILITÉ (nuit)
        // ================================================================
        $schedule->job(new \App\Jobs\Comptabilite\RapprocherFacturesFournisseurs())
            ->dailyAt('02:00')
            ->name('compta.rapprochement');

        // ================================================================
        // STOCK (recalcul hebdomadaire)
        // ================================================================
        $schedule->job(new \App\Jobs\Stock\RecalculerCmupMasse())
            ->weeklyOn(7, '03:00') // Dimanche nuit
            ->name('stock.cmup');

        $schedule->job(new \App\Jobs\Stock\SynchroniserStockChantiers())
            ->dailyAt('03:30')
            ->name('stock.sync');

        // ================================================================
        // MAINTENANCE
        // ================================================================
        $schedule->job(new \App\Jobs\Maintenance\NettoyerNotificationsAnciennes(90))
            ->weekly()
            ->name('maint.notifications');

        $schedule->job(new \App\Jobs\Maintenance\NettoyerFichiersTemporaires(7))
            ->weekly()
            ->name('maint.fichiers_tmp');

        $schedule->job(new \App\Jobs\Maintenance\NettoyerJournalActivites(730))
            ->monthlyOn(1, '02:00')
            ->name('maint.journal');

        $schedule->job(new \App\Jobs\Maintenance\ArchiverAnciensChantiers(5))
            ->yearlyOn(1, 1, '02:00')
            ->name('maint.archives');

        // ================================================================
        // BACKUPS
        // ================================================================
        $schedule->job(new \App\Jobs\Backups\SauvegarderBaseDonnees())
            ->dailyAt('01:00')
            ->name('backup.db')
            ->withoutOverlapping()
            ->onOneServer();

        $schedule->job(new \App\Jobs\Backups\SauvegarderFichiers())
            ->weeklyOn(7, '01:30') // Dimanche 01h30
            ->name('backup.files')
            ->withoutOverlapping()
            ->onOneServer();

        // ================================================================
        // QUEUE HEALTH
        // ================================================================
        $schedule->command('queue:prune-failed --hours=168')
            ->weekly()
            ->name('queue.prune');

        $schedule->command('queue:prune-batches --hours=48')
            ->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}