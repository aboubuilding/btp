<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContratSousTraitantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationSousTraitantController;
use App\Http\Controllers\FactureSousTraitantController;
use App\Http\Controllers\PaiementSousTraitantController;
use App\Http\Controllers\PlanComptableController;
use App\Http\Controllers\SoustraitantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/check-session', [LoginController::class, 'checkSession'])->name('check.session');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Les users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::post('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
        Route::get('/search', [UserController::class, 'search'])->name('search');
    });

// Sous traitants
    Route::prefix('soustraitants')->name('soustraitants.')->group(function () {
        Route::get('/', [SoustraitantController::class, 'index'])->name('index');
        Route::post('/', [SoustraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [SoustraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [SoustraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [SoustraitantController::class, 'restore'])->name('restore');
        Route::post('/{id}/update-status', [SoustraitantController::class, 'updateStatus'])->name('update-status');
        Route::get('/search', [SoustraitantController::class, 'search'])->name('search');
        Route::get('/stats', [SoustraitantController::class, 'getStats'])->name('stats');
    });

// Clients
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::put('/{id}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClientController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [ClientController::class, 'restore'])->name('restore');
        Route::post('/{id}/toggle-active', [ClientController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/search', [ClientController::class, 'search'])->name('search');
        Route::get('/{id}/projects', [ClientController::class, 'getClientProjects'])->name('projects');
    });

// Gestion des contrat des sous traitants
    Route::prefix('contrats-sous-traitants')->name('contrats-sous-traitants.')->group(function () {
        Route::get('/', [ContratSousTraitantController::class, 'index'])->name('index');
        Route::post('/', [ContratSousTraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [ContratSousTraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [ContratSousTraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [ContratSousTraitantController::class, 'restore'])->name('restore');
        Route::post('/{id}/update-status', [ContratSousTraitantController::class, 'updateStatus'])->name('update-status');
        Route::get('/{id}/download', [ContratSousTraitantController::class, 'downloadFile'])->name('download');
        Route::get('/search', [ContratSousTraitantController::class, 'search'])->name('search');
        Route::get('/generate-numero', [ContratSousTraitantController::class, 'generateNumero'])->name('generate-numero');
    });

// Gestion des Factures  des sous traitants
    Route::prefix('factures-sous-traitants')->name('factures-sous-traitants.')->group(function () {
        Route::get('/', [FactureSousTraitantController::class, 'index'])->name('index');
        Route::post('/', [FactureSousTraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [FactureSousTraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [FactureSousTraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [FactureSousTraitantController::class, 'restore'])->name('restore');
        Route::post('/{id}/update-status', [FactureSousTraitantController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/marquer-payee', [FactureSousTraitantController::class, 'marquerPayee'])->name('marquer-payee');
        Route::post('/{id}/contester', [FactureSousTraitantController::class, 'contester'])->name('contester');
        Route::get('/search', [FactureSousTraitantController::class, 'search'])->name('search');
        Route::get('/generate-numero', [FactureSousTraitantController::class, 'generateNumero'])->name('generate-numero');
        Route::get('/en-retard', [FactureSousTraitantController::class, 'getEnRetard'])->name('en-retard');
    });


// Gestion des paiements   des sous traitants
    Route::prefix('paiements-sous-traitants')->name('paiements-sous-traitants.')->group(function () {
        Route::get('/', [PaiementSousTraitantController::class, 'index'])->name('index');
        Route::post('/', [PaiementSousTraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [PaiementSousTraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaiementSousTraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [PaiementSousTraitantController::class, 'restore'])->name('restore');
        Route::get('/search', [PaiementSousTraitantController::class, 'search'])->name('search');
        Route::get('/by-facture/{factureId}', [PaiementSousTraitantController::class, 'getByFacture'])->name('by-facture');
    });

// Gestion des evaluations    des sous traitants
    Route::prefix('evaluations-sous-traitants')->name('evaluations-sous-traitants.')->group(function () {
        Route::get('/', [EvaluationSousTraitantController::class, 'index'])->name('index');
        Route::post('/', [EvaluationSousTraitantController::class, 'store'])->name('store');
        Route::put('/{id}', [EvaluationSousTraitantController::class, 'update'])->name('update');
        Route::delete('/{id}', [EvaluationSousTraitantController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [EvaluationSousTraitantController::class, 'restore'])->name('restore');
        Route::get('/search', [EvaluationSousTraitantController::class, 'search'])->name('search');
        Route::get('/by-soustraitant/{soustraitantId}', [EvaluationSousTraitantController::class, 'getBySoustraitant'])->name('by-soustraitant');
        Route::get('/last', [EvaluationSousTraitantController::class, 'getLast'])->name('last');
    });

// Gestion des comptes
    Route::prefix('plan-comptable')->name('plan-comptable.')->group(function () {
        Route::get('/', [PlanComptableController::class, 'index'])->name('index');
        Route::post('/', [PlanComptableController::class, 'store'])->name('store');
        Route::put('/{id}', [PlanComptableController::class, 'update'])->name('update');
        Route::delete('/{id}', [PlanComptableController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [PlanComptableController::class, 'restore'])->name('restore');
        Route::post('/{id}/reorder', [PlanComptableController::class, 'reorder'])->name('reorder');
        Route::get('/search', [PlanComptableController::class, 'search'])->name('search');
        Route::get('/type/{type}', [PlanComptableController::class, 'getByType'])->name('by-type');
        Route::get('/parents', [PlanComptableController::class, 'getParents'])->name('parents');
        Route::get('/tree', [PlanComptableController::class, 'getTree'])->name('tree');
    });

// Gestion des comptes
    Route::prefix('ecritures-comptables')->name('ecritures-comptables.')->group(function () {
        Route::get('/', [EcritureComptableController::class, 'index'])->name('index');
        Route::post('/', [EcritureComptableController::class, 'store'])->name('store');
        Route::put('/{id}', [EcritureComptableController::class, 'update'])->name('update');
        Route::delete('/{id}', [EcritureComptableController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [EcritureComptableController::class, 'restore'])->name('restore');
        Route::post('/{id}/valider', [EcritureComptableController::class, 'valider'])->name('valider');
        Route::post('/{id}/contre-passer', [EcritureComptableController::class, 'contrePasser'])->name('contre-passer');
        Route::get('/search', [EcritureComptableController::class, 'search'])->name('search');
        Route::get('/{id}/lignes', [EcritureComptableController::class, 'getLignes'])->name('lignes');
        Route::get('/grand-livre', [EcritureComptableController::class, 'getGrandLivre'])->name('grand-livre');
        Route::get('/generate-numero', [EcritureComptableController::class, 'generateNumero'])->name('generate-numero');
    });


});

// Route par défaut pour le tableau de bord
Route::get('/', function () {
    return redirect()->route('dashboard');
});
