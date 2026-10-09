<?php
/**
 * BTPManager — Commandes Artisan custom
 */

use Illuminate\Support\Facades\Artisan;

Artisan::command('btp:info', function () {
    $this->info('BTPManager v1.0.0');
    $this->info('Application de gestion BTP — Laravel ' . app()->version());
    $this->newLine();
    $this->table(['Module', 'Statut'], [
        ['Chantiers',  \App\Domain\Execution\Models\Projet::where('etat', 1)->count() . ' actifs'],
        ['Employés',   \App\Domain\Personnel\Models\Employe::where('etat', 1)->count() . ' actifs'],
        ['Factures',   \App\Domain\Finances\Models\Facture::count() . ' émises'],
        ['Stock',      number_format((float) \App\Domain\Approvisionnement\Models\NiveauStock::sum(\DB::raw('quantite * cmup')), 0, ',', ' ') . ' FCFA'],
    ]);
})->purpose('Affiche un résumé de l\'application');

Artisan::command('btp:stats {--periode=mois}', function () {
    $periode = $this->option('periode');
    $this->info("📊 Statistiques ({$periode})");
    $this->newLine();

    $debut = match ($periode) {
        'jour'    => now()->startOfDay(),
        'semaine' => now()->startOfWeek(),
        'mois'    => now()->startOfMonth(),
        'annee'   => now()->startOfYear(),
        default   => now()->startOfMonth(),
    };

    $stats = [
        'Chantiers créés'    => \App\Domain\Execution\Models\Projet::where('created_at', '>=', $debut)->count(),
        'Factures émises'    => \App\Domain\Finances\Models\Facture::where('created_at', '>=', $debut)->count(),
        'CA facturé'         => number_format((float) \App\Domain\Finances\Models\Facture::where('created_at', '>=', $debut)->sum('montant_ttc'), 0, ',', ' ') . ' FCFA',
        'Dépenses'           => number_format((float) \App\Domain\Finances\Models\Depense::where('created_at', '>=', $debut)->sum('montant'), 0, ',', ' ') . ' FCFA',
        'Incidents déclarés' => \App\Domain\QHSE\Models\IncidentSecurite::where('created_at', '>=', $debut)->count(),
    ];

    foreach ($stats as $label => $valeur) {
        $this->line("  → {$label} : <fg=green>{$valeur}</fg=green>");
    }
})->purpose('Affiche les statistiques par période');

Artisan::command('btp:verifier-seuils', function () {
    $this->info('🔍 Vérification des seuils de stock...');
    $count = 0;

    \App\Domain\Approvisionnement\Models\NiveauStock::with(['materiau', 'entrepot'])
        ->whereHas('materiau', fn($q) => $q->where('etat', 1))
        ->whereRaw('quantite <= (SELECT seuil_alerte_stock_min FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)')
        ->chunkById(100, function ($niveaux) use (&$count) {
            foreach ($niveaux as $n) {
                event(new \App\Domain\Approvisionnement\Events\StockSousSeuil($n->materiau, $n));
                $count++;
            }
        });

    $this->info("✅ {$count} alerte(s) déclenchée(s)");
})->purpose('Vérifie et déclenche les alertes de stock');

Artisan::command('btp:recalculer-cmup', function () {
    $this->info('🔄 Recalcul des CMUP...');
    dispatch(new \App\Jobs\Stock\RecalculerCmupMasse());
    $this->info('✅ Job de recalcul lancé en arrière-plan');
})->purpose('Recalcule les CMUP de tous les niveaux de stock');

Artisan::command('btp:purger-journal {--jours=730}', function () {
    $jours = (int) $this->option('jours');
    $this->warn("⚠️  Purge du journal avant {$jours} jours...");

    if (!$this->confirm('Confirmer ?')) {
        $this->info('Annulé.');
        return;
    }

    $supprimes = app(\App\Domain\Socle\Services\JournalService::class)->purgerAvant($jours);
    $this->info("✅ {$supprimes} entrées supprimées");
})->purpose('Purge le journal d\'activités');

Artisan::command('btp:demo-reset', function () {
    $this->warn('⚠️  Réinitialisation des données de démonstration...');

    if (!app()->environment(['local', 'development'])) {
        $this->error('❌ Commande disponible uniquement en local/development.');
        return;
    }

    if (!$this->confirm('Toutes les données seront effacées. Confirmer ?')) {
        return;
    }

    Artisan::call('migrate:fresh', ['--seed' => true]);
    $this->info('✅ Base de données réinitialisée avec les données de démo');
})->purpose('Réinitialise la base avec les données de démonstration');

Artisan::command('btp:backup', function () {
    $this->info('💾 Lancement de la sauvegarde...');
    dispatch(new \App\Jobs\Backups\SauvegarderBaseDonnees());
    dispatch(new \App\Jobs\Backups\SauvegarderFichiers());
    $this->info('✅ Jobs de sauvegarde lancés');
})->purpose('Lance une sauvegarde manuelle');