<?php
/**
 * BTPManager — Routes Web
 * Structure : 6 menus du CDC + Admin
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| REDIRECTION RACINE
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('dashboard'));

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION (invités uniquement)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login',  [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('register',  [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.post');

    // Mot de passe oublié
    Route::get('forgot-password',  [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'send'])->name('password.email');

    // Réinitialisation
    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('reset-password',       [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ZONE AUTHENTIFIÉE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified.optional'])->group(function () {

    // ============================================================
    // DASHBOARD & RECHERCHE
    // ============================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search',    [SearchController::class, 'search'])->name('search.global');
    Route::get('/check-session', fn() => response()->json(['authenticated' => auth()->check()]))
        ->name('check.session');

    // ============================================================
    // MENU 1 — TABLEAU DE BORD
    // ============================================================
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('chantiers-en-cours', [DashboardController::class, 'chantiersEnCours'])->name('chantiers');
        Route::get('alertes',            [DashboardController::class, 'alertes'])->name('alertes');
        Route::get('indicateurs',        [DashboardController::class, 'indicateurs'])->name('indicateurs');
    });

    // ============================================================
    // MENU 2 — COMMERCIAL ET CHANTIERS
    // ============================================================
    Route::prefix('commercial')->name('commercial.')->group(function () {

        // ---------- CLIENTS ----------
        Route::resource('clients', \App\Http\Controllers\Commercial\ClientController::class);
        Route::post('clients/{client}/desactiver', [\App\Http\Controllers\Commercial\ClientController::class, 'destroy'])
            ->name('clients.desactiver');

        // ---------- DEVIS ----------
        Route::resource('devis', \App\Http\Controllers\Commercial\DevisController::class);
        Route::post('devis/{devis}/envoyer',    [\App\Http\Controllers\Commercial\DevisController::class, 'envoyer'])->name('devis.envoyer');
        Route::post('devis/{devis}/accepter',   [\App\Http\Controllers\Commercial\DevisController::class, 'accepter'])->name('devis.accepter');
        Route::post('devis/{devis}/refuser',    [\App\Http\Controllers\Commercial\DevisController::class, 'refuser'])->name('devis.refuser');
        Route::post('devis/{devis}/dupliquer',  [\App\Http\Controllers\Commercial\DevisController::class, 'dupliquer'])->name('devis.dupliquer');

        // ---------- MARCHÉS ----------
        Route::resource('marches', \App\Http\Controllers\Commercial\MarcheController::class);
        Route::post('marches/{marche}/signer',         [\App\Http\Controllers\Commercial\MarcheController::class, 'signer'])->name('marches.signer');
        Route::post('marches/{marche}/creer-chantier', [\App\Http\Controllers\Commercial\MarcheController::class, 'creerChantier'])->name('marches.creer_chantier');

        // ---------- AVENANTS (modale) ----------
        Route::get('marches/{marche}/avenant/create', [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'create'])->name('avenant.create');
        Route::post('marches/{marche}/avenant',       [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'store'])->name('avenant.store');
        Route::get('avenant/{avenant}/edit',          [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'edit'])->name('avenant.edit');
        Route::put('avenant/{avenant}',               [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'update'])->name('avenant.update');
        Route::post('avenant/{avenant}/signer',       [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'signer'])->name('avenant.signer');
        Route::delete('avenant/{avenant}',            [\App\Http\Controllers\Commercial\AvenantMarcheController::class, 'destroy'])->name('avenant.destroy');

        // ---------- CAUTIONS (modale) ----------
        Route::get('marches/{marche}/caution/create', [\App\Http\Controllers\Commercial\CautionMarcheController::class, 'create'])->name('caution.create');
        Route::post('marches/{marche}/caution',       [\App\Http\Controllers\Commercial\CautionMarcheController::class, 'store'])->name('caution.store');
        Route::get('caution/{caution}/edit',          [\App\Http\Controllers\Commercial\CautionMarcheController::class, 'edit'])->name('caution.edit');
        Route::put('caution/{caution}',               [\App\Http\Controllers\Commercial\CautionMarcheController::class, 'update'])->name('caution.update');
        Route::delete('caution/{caution}',            [\App\Http\Controllers\Commercial\CautionMarcheController::class, 'destroy'])->name('caution.destroy');
    });

    // ============================================================
    // CHANTIERS / PROJETS
    // ============================================================
    Route::prefix('chantiers')->name('projets.')->group(function () {

        // ---------- PROJETS ----------
        Route::resource('projets', \App\Http\Controllers\Chantier\ProjetController::class);
        Route::post('projets/{projet}/demarrer',   [\App\Http\Controllers\Chantier\ProjetController::class, 'demarrer'])->name('projets.demarrer');
        Route::post('projets/{projet}/suspendre',  [\App\Http\Controllers\Chantier\ProjetController::class, 'suspendre'])->name('projets.suspendre');
        Route::post('projets/{projet}/terminer',   [\App\Http\Controllers\Chantier\ProjetController::class, 'terminer'])->name('projets.terminer');

        // ---------- PHASES (modale) ----------
        Route::get('projets/{projet}/phases/create', [\App\Http\Controllers\Chantier\PhaseController::class, 'create'])->name('phases.create');
        Route::post('projets/{projet}/phases',       [\App\Http\Controllers\Chantier\PhaseController::class, 'store'])->name('phases.store');
        Route::get('phases/{phase}/edit',            [\App\Http\Controllers\Chantier\PhaseController::class, 'edit'])->name('phases.edit');
        Route::put('phases/{phase}',                 [\App\Http\Controllers\Chantier\PhaseController::class, 'update'])->name('phases.update');
        Route::delete('phases/{phase}',              [\App\Http\Controllers\Chantier\PhaseController::class, 'destroy'])->name('phases.destroy');

        // ---------- JALONS (modale) ----------
        Route::get('projets/{projet}/jalons/create', [\App\Http\Controllers\Chantier\JalonController::class, 'create'])->name('jalons.create');
        Route::post('projets/{projet}/jalons',       [\App\Http\Controllers\Chantier\JalonController::class, 'store'])->name('jalons.store');
        Route::get('jalons/{jalon}/edit',            [\App\Http\Controllers\Chantier\JalonController::class, 'edit'])->name('jalons.edit');
        Route::put('jalons/{jalon}',                 [\App\Http\Controllers\Chantier\JalonController::class, 'update'])->name('jalons.update');
        Route::delete('jalons/{jalon}',              [\App\Http\Controllers\Chantier\JalonController::class, 'destroy'])->name('jalons.destroy');

        // ---------- TÂCHES ----------
        Route::get('projets/{projet}/taches',              [\App\Http\Controllers\Chantier\TacheController::class, 'index'])->name('taches.index');
        Route::get('projets/{projet}/taches/create',       [\App\Http\Controllers\Chantier\TacheController::class, 'create'])->name('taches.create');
        Route::post('projets/{projet}/taches',             [\App\Http\Controllers\Chantier\TacheController::class, 'store'])->name('taches.store');
        Route::get('taches/{tache}/edit',                  [\App\Http\Controllers\Chantier\TacheController::class, 'edit'])->name('taches.edit');
        Route::put('taches/{tache}',                       [\App\Http\Controllers\Chantier\TacheController::class, 'update'])->name('taches.update');
        Route::delete('taches/{tache}',                    [\App\Http\Controllers\Chantier\TacheController::class, 'destroy'])->name('taches.destroy');
        Route::post('taches/{tache}/avancement',           [\App\Http\Controllers\Chantier\TacheController::class, 'mettreAJourAvancement'])->name('taches.avancement');

        // ---------- ÉQUIPE (modale) ----------
        Route::get('projets/{projet}/equipe/create',       [\App\Http\Controllers\Chantier\EquipeController::class, 'create'])->name('equipe.create');
        Route::post('projets/{projet}/equipe',             [\App\Http\Controllers\Chantier\EquipeController::class, 'store'])->name('equipe.store');
        Route::delete('projets/{projet}/equipe/{employe}', [\App\Http\Controllers\Chantier\EquipeController::class, 'destroy'])->name('equipe.destroy');

        // ---------- BUDGETS (modale) ----------
        Route::get('projets/{projet}/budget/create',       [\App\Http\Controllers\Chantier\LigneBudgetController::class, 'create'])->name('budgets.create');
        Route::post('projets/{projet}/budget',             [\App\Http\Controllers\Chantier\LigneBudgetController::class, 'store'])->name('budgets.store');
        Route::put('budgets/{ligne}',                      [\App\Http\Controllers\Chantier\LigneBudgetController::class, 'update'])->name('budgets.update');
        Route::delete('budgets/{ligne}',                   [\App\Http\Controllers\Chantier\LigneBudgetController::class, 'destroy'])->name('budgets.destroy');

        // ---------- PLANNING GANTT ----------
        Route::get('projets/{projet}/planning',            [\App\Http\Controllers\Chantier\PlanningController::class, 'gantt'])->name('planning.gantt');
        Route::get('projets/{projet}/planning/data',       [\App\Http\Controllers\Chantier\PlanningController::class, 'data'])->name('planning.data');
        Route::put('taches/{tache}/planning',              [\App\Http\Controllers\Chantier\PlanningController::class, 'updateTache'])->name('taches.planning.update');

        // ---------- AVANCEMENT / JOURNAL ----------
        Route::get('journal',                     [\App\Http\Controllers\Chantier\AvancementController::class, 'index'])->name('journal.index');
        Route::get('projets/{projet}/journal/create', [\App\Http\Controllers\Chantier\AvancementController::class, 'create'])->name('journal.create');
        Route::post('projets/{projet}/avancements',   [\App\Http\Controllers\Chantier\AvancementController::class, 'store'])->name('avancements.store');
        Route::post('avancements/{avancement}/valider', [\App\Http\Controllers\Chantier\AvancementController::class, 'valider'])->name('avancements.valider');
    });

    // ============================================================
    // SITUATIONS DE TRAVAUX
    // ============================================================
    Route::prefix('situations')->name('situations.')->group(function () {
        // ---------- ATTACHEMENTS ----------
        Route::resource('attachements', \App\Http\Controllers\Situation\AttachementController::class);
        Route::post('attachements/{attachement}/valider', [\App\Http\Controllers\Situation\AttachementController::class, 'valider'])->name('attachements.valider');

        // ---------- SITUATIONS ----------
        Route::resource('situations', \App\Http\Controllers\Situation\SituationController::class);
        Route::post('situations/{situation}/valider',    [\App\Http\Controllers\Situation\SituationController::class, 'valider'])->name('situations.valider');
        Route::post('situations/{situation}/transmettre',[\App\Http\Controllers\Situation\SituationController::class, 'transmettre'])->name('situations.transmettre');
        Route::post('situations/{situation}/approuver',  [\App\Http\Controllers\Situation\SituationController::class, 'approuver'])->name('situations.approuver');
        Route::post('situations/{situation}/facturer',   [\App\Http\Controllers\Situation\SituationController::class, 'facturer'])->name('situations.facturer');
        Route::get('situations/{situation}/pdf',         [\App\Http\Controllers\Situation\SituationController::class, 'pdf'])->name('situations.pdf');
    });

    // ============================================================
    // MENU 3 — APPROVISIONNEMENT ET LOGISTIQUE
    // ============================================================
    Route::prefix('logistique')->name('logistique.')->group(function () {

        // ---------- FOURNISSEURS ----------
        Route::resource('fournisseurs', \App\Http\Controllers\Approvisionnement\FournisseurController::class);

        // ---------- DEMANDES D'ACHAT ----------
        Route::resource('demandes-achat', \App\Http\Controllers\Approvisionnement\DemandeAchatController::class);
        Route::post('demandes-achat/{demande}/valider', [\App\Http\Controllers\Approvisionnement\DemandeAchatController::class, 'valider'])->name('demandes-achat.valider');
        Route::post('demandes-achat/{demande}/rejeter', [\App\Http\Controllers\Approvisionnement\DemandeAchatController::class, 'rejeter'])->name('demandes-achat.rejeter');

        // ---------- BONS DE COMMANDE ----------
        Route::resource('bons-commande', \App\Http\Controllers\Approvisionnement\BonCommandeController::class);
        Route::post('bons-commande/{bon}/envoyer',  [\App\Http\Controllers\Approvisionnement\BonCommandeController::class, 'envoyer'])->name('bons-commande.envoyer');
        Route::post('bons-commande/{bon}/valider',  [\App\Http\Controllers\Approvisionnement\BonCommandeController::class, 'valider'])->name('bons-commande.valider');
        Route::post('bons-commande/{bon}/annuler',  [\App\Http\Controllers\Approvisionnement\BonCommandeController::class, 'annuler'])->name('bons-commande.annuler');

        // ---------- LIVRAISONS ----------
        Route::resource('livraisons', \App\Http\Controllers\Approvisionnement\LivraisonController::class);
        Route::post('livraisons/{livraison}/refuser', [\App\Http\Controllers\Approvisionnement\LivraisonController::class, 'refuser'])->name('livraisons.refuser');

        // ---------- STOCK ----------
        // Catégories (modale)
        Route::resource('categories', \App\Http\Controllers\Stock\CategorieMateriauController::class)->except(['show']);

        // Matériaux
        Route::resource('materiaux', \App\Http\Controllers\Stock\MateriauController::class);

        // Entrepôts (modale)
        Route::resource('entrepots', \App\Http\Controllers\Stock\EntrepotController::class)->except(['show']);

        // Niveaux de stock
        Route::get('stocks',                   [\App\Http\Controllers\Stock\StockController::class, 'index'])->name('stocks.index');
        Route::get('stocks/mouvement/create',  [\App\Http\Controllers\Stock\StockController::class, 'createMouvement'])->name('stocks.mouvement.create');
        Route::post('stocks/mouvement',        [\App\Http\Controllers\Stock\StockController::class, 'storeMouvement'])->name('stocks.mouvement.store');

        // Transferts
        Route::resource('transferts', \App\Http\Controllers\Stock\TransfertController::class)->except(['show']);
        Route::post('transferts/{transfert}/approuver', [\App\Http\Controllers\Stock\TransfertController::class, 'approuver'])->name('transferts.approuver');
        Route::post('transferts/{transfert}/rejeter',   [\App\Http\Controllers\Stock\TransfertController::class, 'rejeter'])->name('transferts.rejeter');

        // Inventaires
        Route::resource('inventaires', \App\Http\Controllers\Stock\InventaireController::class);
        Route::post('inventaires/{inventaire}/valider', [\App\Http\Controllers\Stock\InventaireController::class, 'valider'])->name('inventaires.valider');
    });

    // ============================================================
    // MENU 3bis — MATÉRIEL ET ENGINS
    // ============================================================
    Route::prefix('materiel')->name('materiel.')->group(function () {

        // ---------- ÉQUIPEMENTS ----------
        Route::resource('equipements', \App\Http\Controllers\Materiel\EquipementController::class);
        Route::post('equipements/{equipement}/changer-statut', [\App\Http\Controllers\Materiel\EquipementController::class, 'changerStatut'])->name('equipements.changer-statut');

        // ---------- CATÉGORIES (modale) ----------
        Route::resource('categories', \App\Http\Controllers\Materiel\CategorieEquipementController::class)->except(['show']);

        // ---------- AFFECTATIONS (modale) ----------
        Route::get('equipements/{equipement}/affectations/create', [\App\Http\Controllers\Materiel\AffectationController::class, 'create'])->name('affectations.create');
        Route::post('equipements/{equipement}/affectations',       [\App\Http\Controllers\Materiel\AffectationController::class, 'store'])->name('affectations.store');
        Route::put('affectations/{affectation}/fermer',            [\App\Http\Controllers\Materiel\AffectationController::class, 'fermer'])->name('affectations.fermer');

        // ---------- MAINTENANCES ----------
        Route::resource('maintenances', \App\Http\Controllers\Materiel\MaintenanceController::class)->except(['show', 'destroy']);

        // ---------- PANNES (modale) ----------
        Route::get('equipements/{equipement}/pannes/create', [\App\Http\Controllers\Materiel\PanneController::class, 'create'])->name('pannes.create');
        Route::post('equipements/{equipement}/pannes',       [\App\Http\Controllers\Materiel\PanneController::class, 'store'])->name('pannes.store');
        Route::get('pannes/{panne}/edit',                    [\App\Http\Controllers\Materiel\PanneController::class, 'edit'])->name('pannes.edit');
        Route::put('pannes/{panne}',                         [\App\Http\Controllers\Materiel\PanneController::class, 'update'])->name('pannes.update');
        Route::post('pannes/{panne}/cloturer',               [\App\Http\Controllers\Materiel\PanneController::class, 'cloturer'])->name('pannes.cloturer');

        // ---------- CARBURANT (modale) ----------
        Route::get('equipements/{equipement}/carburant/create', [\App\Http\Controllers\Materiel\CarburantController::class, 'create'])->name('carburant.create');
        Route::post('equipements/{equipement}/carburant',       [\App\Http\Controllers\Materiel\CarburantController::class, 'store'])->name('carburant.store');
        Route::get('carburant/{releve}/edit',                   [\App\Http\Controllers\Materiel\CarburantController::class, 'edit'])->name('carburant.edit');
        Route::put('carburant/{releve}',                        [\App\Http\Controllers\Materiel\CarburantController::class, 'update'])->name('carburant.update');

        // ---------- DOCUMENTS (modale) ----------
        Route::get('equipements/{equipement}/documents/create', [\App\Http\Controllers\Materiel\DocumentEquipementController::class, 'create'])->name('documents.create');
        Route::post('equipements/{equipement}/documents',       [\App\Http\Controllers\Materiel\DocumentEquipementController::class, 'store'])->name('documents.store');
    });

    // ============================================================
    // MENU 4 — PERSONNEL ET PAIE
    // ============================================================
    Route::prefix('rh')->name('rh.')->group(function () {

        // ---------- EMPLOYÉS ----------
        Route::resource('employes', \App\Http\Controllers\Personnel\EmployeController::class);

        // ---------- CONTRATS ----------
        Route::resource('contrats', \App\Http\Controllers\Personnel\ContratController::class)->except(['show', 'destroy']);
        Route::post('contrats/{contrat}/resilier', [\App\Http\Controllers\Personnel\ContratController::class, 'resilier'])->name('contrats.resilier');

        // ---------- RÉFÉRENTIELS ----------
        Route::get('referentiels', [\App\Http\Controllers\Personnel\ReferentielController::class, 'index'])->name('referentiels.index');

        // Départements (modale)
        Route::resource('departements', \App\Http\Controllers\Personnel\DepartementController::class)->except(['index', 'show']);

        // Postes (modale)
        Route::resource('postes', \App\Http\Controllers\Personnel\PosteController::class)->except(['index', 'show']);

        // Types de congés (modale)
        Route::resource('types-conges', \App\Http\Controllers\Personnel\TypeCongeController::class)->except(['index', 'show']);

        // ---------- CONGÉS ----------
        Route::resource('conges', \App\Http\Controllers\Personnel\CongeController::class)->except(['show']);
        Route::post('conges/{conge}/approuver', [\App\Http\Controllers\Personnel\CongeController::class, 'approuver'])->name('conges.approuver');
        Route::post('conges/{conge}/refuser',   [\App\Http\Controllers\Personnel\CongeController::class, 'refuser'])->name('conges.refuser');

        // ---------- POINTAGES ----------
        Route::get('pointages', [\App\Http\Controllers\Personnel\PresenceController::class, 'index'])->name('pointages.index');
        Route::get('projets/{projet}/pointages/saisie', [\App\Http\Controllers\Personnel\PresenceController::class, 'create'])->name('pointages.saisie');
        Route::post('pointages', [\App\Http\Controllers\Personnel\PresenceController::class, 'store'])->name('pointages.store');
        Route::post('pointages/valider', [\App\Http\Controllers\Personnel\PresenceController::class, 'valider'])->name('pointages.valider');

        // ---------- PÉRIODES DE PAIE ----------
        Route::resource('periodes-paie', \App\Http\Controllers\Personnel\PeriodePaieController::class);
        Route::post('periodes-paie/{periode}/generer',  [\App\Http\Controllers\Personnel\PeriodePaieController::class, 'generer'])->name('periodes-paie.generer');
        Route::post('periodes-paie/{periode}/cloturer', [\App\Http\Controllers\Personnel\PeriodePaieController::class, 'cloturer'])->name('periodes-paie.cloturer');

        // ---------- BULLETINS ----------
        Route::get('bulletins',                [\App\Http\Controllers\Personnel\BulletinPaieController::class, 'index'])->name('bulletins.index');
        Route::get('bulletins/{bulletin}',     [\App\Http\Controllers\Personnel\BulletinPaieController::class, 'show'])->name('bulletins.show');
        Route::post('bulletins/{bulletin}/valider',    [\App\Http\Controllers\Personnel\BulletinPaieController::class, 'valider'])->name('bulletins.valider');
        Route::post('bulletins/{bulletin}/marquer-paye',[\App\Http\Controllers\Personnel\BulletinPaieController::class, 'marquerPaye'])->name('bulletins.marquer-paye');
        Route::get('bulletins/{bulletin}/pdf',         [\App\Http\Controllers\Personnel\BulletinPaieController::class, 'pdf'])->name('bulletins.pdf');

        // ---------- AVANCES SALAIRES ----------
        Route::resource('avances', \App\Http\Controllers\Personnel\AvanceSalaireController::class)->except(['show', 'destroy']);
        Route::post('avances/{avance}/approuver', [\App\Http\Controllers\Personnel\AvanceSalaireController::class, 'approuver'])->name('avances.approuver');
    });

    // ============================================================
    // MENU 5 — FINANCES ET SOUS-TRAITANCE
    // ============================================================
    Route::prefix('finances')->name('finances.')->group(function () {

        // ---------- FACTURES ----------
        Route::resource('factures', \App\Http\Controllers\Finances\FactureController::class);
        Route::get('factures/{facture}/pdf',     [\App\Http\Controllers\Finances\FactureController::class, 'pdf'])->name('factures.pdf');
        Route::get('factures/{facture}/apercu',  [\App\Http\Controllers\Finances\FactureController::class, 'preview'])->name('factures.preview');
        Route::post('factures/{facture}/annuler',[\App\Http\Controllers\Finances\FactureController::class, 'annuler'])->name('factures.annuler');

        // ---------- PAIEMENTS ----------
        Route::resource('paiements', \App\Http\Controllers\Finances\PaiementController::class)->except(['show', 'edit', 'update', 'destroy']);

        // ---------- DÉPENSES ----------
        Route::resource('depenses', \App\Http\Controllers\Finances\DepenseController::class)->except(['show', 'edit', 'update', 'destroy']);
        Route::post('depenses/{depense}/approuver', [\App\Http\Controllers\Finances\DepenseController::class, 'approuver'])->name('depenses.approuver');
        Route::post('depenses/{depense}/rejeter',   [\App\Http\Controllers\Finances\DepenseController::class, 'rejeter'])->name('depenses.rejeter');

        // ---------- CAISSES (modale) ----------
        Route::resource('caisses', \App\Http\Controllers\Finances\CaisseController::class)->except(['show', 'destroy']);
        Route::post('caisses/{caisse}/alimenter', [\App\Http\Controllers\Finances\CaisseController::class, 'alimenter'])->name('caisses.alimenter');

        // ---------- COMPTES BANCAIRES (modale) ----------
        Route::resource('comptes-bancaires', \App\Http\Controllers\Finances\CompteBancaireController::class)->except(['show', 'destroy']);

        // ---------- ÉCRITURES COMPTABLES ----------
        Route::resource('ecritures', \App\Http\Controllers\Finances\EcritureComptableController::class)->except(['edit', 'update', 'destroy']);
        Route::post('ecritures/{ecriture}/valider', [\App\Http\Controllers\Finances\EcritureComptableController::class, 'valider'])->name('ecritures.valider');

        // ---------- PLAN COMPTABLE ----------
        Route::resource('plan-comptable', \App\Http\Controllers\Finances\PlanComptableController::class)->except(['show', 'destroy']);

        // ---------- EXERCICES FISCAUX ----------
        Route::get('exercices', [\App\Http\Controllers\Finances\ExerciceFiscalController::class, 'index'])->name('exercices.index');
        Route::post('exercices', [\App\Http\Controllers\Finances\ExerciceFiscalController::class, 'store'])->name('exercices.store');
        Route::post('exercices/{exercice}/cloturer', [\App\Http\Controllers\Finances\ExerciceFiscalController::class, 'cloturer'])->name('exercices.cloturer');
    });

    // ============================================================
    // SOUS-TRAITANCE
    // ============================================================
    Route::prefix('sous-traitance')->name('soustraitance.')->group(function () {

        // ---------- SOUS-TRAITANTS ----------
        Route::resource('soustraitants', \App\Http\Controllers\SousTraitance\SoustraitantController::class);
        Route::post('soustraitants/{soustraitant}/blacklister', [\App\Http\Controllers\SousTraitance\SoustraitantController::class, 'blacklister'])->name('soustraitants.blacklister');

        // ---------- CONTRATS ----------
        Route::resource('contrats', \App\Http\Controllers\SousTraitance\ContratSousTraitantController::class);
        Route::post('contrats/{contrat}/resilier', [\App\Http\Controllers\SousTraitance\ContratSousTraitantController::class, 'resilier'])->name('contrats.resilier');

        // ---------- FACTURES ----------
        Route::resource('factures', \App\Http\Controllers\SousTraitance\FactureSousTraitantController::class);

        // ---------- PAIEMENTS (modale) ----------
        Route::get('contrats/{contrat}/paiement/create', [\App\Http\Controllers\SousTraitance\PaiementSousTraitantController::class, 'create'])->name('paiements.create');
        Route::post('contrats/{contrat}/paiement',       [\App\Http\Controllers\SousTraitance\PaiementSousTraitantController::class, 'store'])->name('paiements.store');

        // ---------- ÉVALUATIONS (modale) ----------
        Route::get('soustraitants/{soustraitant}/evaluation/create', [\App\Http\Controllers\SousTraitance\EvaluationSousTraitantController::class, 'create'])->name('evaluations.create');
        Route::post('soustraitants/{soustraitant}/evaluation',       [\App\Http\Controllers\SousTraitance\EvaluationSousTraitantController::class, 'store'])->name('evaluations.store');
    });

    // ============================================================
    // MENU 6 — QHSE ET ADMINISTRATION
    // ============================================================
    Route::prefix('qhse')->name('qhse.')->group(function () {

        // ---------- INCIDENTS ----------
        Route::resource('incidents', \App\Http\Controllers\Qhse\IncidentController::class);
        Route::post('incidents/{incident}/cloturer', [\App\Http\Controllers\Qhse\IncidentController::class, 'cloturer'])->name('incidents.cloturer');

        // ---------- CAUSERIES (modale) ----------
        Route::resource('causeries', \App\Http\Controllers\Qhse\CauserieController::class)->except(['show']);

        // ---------- NON-CONFORMITÉS ----------
        Route::resource('non-conformites', \App\Http\Controllers\Qhse\NonConformiteController::class)->except(['show', 'destroy']);
        Route::post('non-conformites/{nonConformite}/lever', [\App\Http\Controllers\Qhse\NonConformiteController::class, 'lever'])->name('non-conformites.lever');

        // ---------- PV RÉCEPTIONS (modale) ----------
        Route::resource('pv-receptions', \App\Http\Controllers\Qhse\PvReceptionController::class)->except(['edit', 'update', 'destroy']);
        Route::post('pv-receptions/{pv}/signer', [\App\Http\Controllers\Qhse\PvReceptionController::class, 'signer'])->name('pv-receptions.signer');

        // ---------- RÉSERVES (modale) ----------
        Route::get('pv-receptions/{pv}/reserve/create', [\App\Http\Controllers\Qhse\ReserveController::class, 'create'])->name('reserves.create');
        Route::post('pv-receptions/{pv}/reserve',       [\App\Http\Controllers\Qhse\ReserveController::class, 'store'])->name('reserves.store');
        Route::get('reserve/{reserve}/edit',            [\App\Http\Controllers\Qhse\ReserveController::class, 'edit'])->name('reserves.edit');
        Route::put('reserve/{reserve}',                 [\App\Http\Controllers\Qhse\ReserveController::class, 'update'])->name('reserves.update');
        Route::post('reserve/{reserve}/lever',          [\App\Http\Controllers\Qhse\ReserveController::class, 'lever'])->name('reserves.lever');
    });

    // ============================================================
    // ADMINISTRATION
    // ============================================================
    Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {

        // ---------- UTILISATEURS ----------
        Route::resource('users', \App\Http\Controllers\Socle\UserController::class)->except(['show']);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Socle\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-actif',   [\App\Http\Controllers\Socle\UserController::class, 'toggleActif'])->name('users.toggle-actif');

        // ---------- RÔLES ----------
        Route::resource('roles', \App\Http\Controllers\Socle\RoleController::class)->except(['show']);
        Route::get('roles/{role}/permissions',  [\App\Http\Controllers\Socle\RoleController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [\App\Http\Controllers\Socle\RoleController::class, 'synchroniser'])->name('roles.permissions.sync');

        // ---------- PERMISSIONS ----------
        Route::resource('permissions', \App\Http\Controllers\Socle\PermissionController::class)->except(['show']);

        // ---------- PARAMÈTRES ----------
        Route::resource('parametres', \App\Http\Controllers\Socle\ParametreController::class)->except(['show']);

        // ---------- JOURNAL D'ACTIVITÉS ----------
        Route::get('journal', [\App\Http\Controllers\Socle\JournalController::class, 'index'])->name('journal.index');
        Route::get('journal/objet/{type}/{id}', [\App\Http\Controllers\Socle\JournalController::class, 'pourObjet'])->name('journal.pour-objet');
    });

    // ============================================================
    // DOCUMENTS (téléchargement / suppression)
    // ============================================================
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('{document}/download', [\App\Http\Controllers\Socle\DocumentController::class, 'download'])->name('download');
        Route::delete('{document}',       [\App\Http\Controllers\Socle\DocumentController::class, 'destroy'])->name('destroy');
    });

    // ============================================================
    // COMMUNICATION
    // ============================================================
    Route::prefix('communications')->name('communications.')->group(function () {
        Route::get('/',                [\App\Http\Controllers\Communication\CommunicationController::class, 'index'])->name('index');
        Route::get('/create',          [\App\Http\Controllers\Communication\CommunicationController::class, 'create'])->name('create');
        Route::post('/',               [\App\Http\Controllers\Communication\CommunicationController::class, 'store'])->name('store');
        Route::get('/{communication}', [\App\Http\Controllers\Communication\CommunicationController::class, 'show'])->name('show');
        Route::post('/{communication}/envoyer', [\App\Http\Controllers\Communication\CommunicationController::class, 'envoyer'])->name('envoyer');
        Route::delete('/{communication}', [\App\Http\Controllers\Communication\CommunicationController::class, 'destroy'])->name('destroy');
    });

    // ============================================================
    // NOTIFICATIONS (interface)
    // ============================================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [\App\Http\Controllers\Socle\NotificationController::class, 'index'])->name('index');
        Route::post('{id}/marquer-lue', [\App\Http\Controllers\Socle\NotificationController::class, 'marquerLue'])->name('marquer-lue');
        Route::post('tout-marquer-lu',  [\App\Http\Controllers\Socle\NotificationController::class, 'toutMarquerLu'])->name('tout-marquer-lu');
    });

    // ============================================================
    // PROFIL UTILISATEUR
    // ============================================================
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/',           [\App\Http\Controllers\Socle\ProfilController::class, 'show'])->name('show');
        Route::put('/',           [\App\Http\Controllers\Socle\ProfilController::class, 'update'])->name('update');
        Route::put('mot-de-passe',[\App\Http\Controllers\Socle\ProfilController::class, 'changerMotDePasse'])->name('mot-de-passe');
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK 404
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => view('errors.404'));