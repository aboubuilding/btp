<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // ============ SOCLE ============
        \App\Domain\Socle\Events\UtilisateurCree::class => [
            \App\Listeners\Socle\EnvoyerEmailBienvenue::class,
            \App\Listeners\Socle\JournaliserUtilisateurCree::class,
        ],
        \App\Domain\Socle\Events\UtilisateurConnecte::class => [
            \App\Listeners\Socle\MettreAJourDerniereConnexion::class,
        ],
        \App\Domain\Socle\Events\ParametreModifie::class => [
            \App\Listeners\Socle\InvaliderCacheParametres::class,
        ],
        \App\Domain\Socle\Events\CommunicationEnvoyee::class => [
            \App\Listeners\Socle\JournaliserCommunication::class,
        ],
        \App\Domain\Socle\Events\CommunicationEchouee::class => [
            \App\Listeners\Socle\NotifierAdminEchec::class,
        ],

        // ============ COMMERCIAL ============
        \App\Domain\Commercial\Events\ClientCree::class => [
            \App\Listeners\Commercial\JournaliserClientCree::class,
        ],
        \App\Domain\Commercial\Events\DevisCree::class => [
            \App\Listeners\Commercial\JournaliserDevisCree::class,
        ],
        \App\Domain\Commercial\Events\DevisAccepte::class => [
            \App\Listeners\Commercial\NotifierDirectionDevisAccepte::class,
            \App\Listeners\Commercial\ProposerCreationMarche::class,
        ],
        \App\Domain\Commercial\Events\MarcheSigne::class => [
            \App\Listeners\Commercial\NotifierMarcheSigne::class,
            \App\Listeners\Commercial\ProposerCreationChantier::class,
        ],
        \App\Domain\Commercial\Events\AvenantSigne::class => [
            \App\Listeners\Commercial\MettreAJourMontantMarche::class,
        ],
        \App\Domain\Commercial\Events\CautionExpireBientot::class => [
            \App\Listeners\Commercial\NotifierCautionExpirante::class,
        ],

        // ============ EXÉCUTION ============
        \App\Domain\Execution\Events\ChantierCree::class => [
            \App\Listeners\Execution\InitialiserBudgetsChantier::class,
            \App\Listeners\Execution\JournaliserChantierCree::class,
        ],
        \App\Domain\Execution\Events\ChantierDemarre::class => [
            \App\Listeners\Execution\NotifierChantierDemarre::class,
        ],
        \App\Domain\Execution\Events\ChantierTermine::class => [
            \App\Listeners\Execution\GenererBilanFinancierChantier::class,
        ],
        \App\Domain\Execution\Events\TacheTerminee::class => [
            \App\Listeners\Execution\MettreAJourAvancementProjet::class,
        ],
        \App\Domain\Execution\Events\TacheEnRetard::class => [
            \App\Listeners\Execution\NotifierTachesEnRetard::class,
        ],
        \App\Domain\Execution\Events\JalonAtteint::class => [
            \App\Listeners\Execution\NotifierJalonAtteint::class,
        ],
        \App\Domain\Execution\Events\JalonsManque::class => [
            \App\Listeners\Execution\NotifierJalonManque::class,
        ],

        // ============ SITUATION ============
        \App\Domain\Execution\Events\SituationCreee::class => [
            \App\Listeners\Situation\JournaliserSituationCreee::class,
        ],
        \App\Domain\Execution\Events\SituationApprouvee::class => [
            \App\Listeners\Situation\GenererFactureClient::class,
            \App\Listeners\Situation\GenererEcritureVente::class,
            \App\Listeners\Situation\NotifierSituationApprouvee::class,
        ],

        // ============ APPROVISIONNEMENT ============
        \App\Domain\Approvisionnement\Events\DemandeAchatCreee::class => [
            \App\Listeners\Approvisionnement\NotifierResponsablesValidation::class,
        ],
        \App\Domain\Approvisionnement\Events\DemandeAchatValidee::class => [
            \App\Listeners\Approvisionnement\NotifierDemandeurValidation::class,
        ],
        \App\Domain\Approvisionnement\Events\BonCommandeCree::class => [
            \App\Listeners\Approvisionnement\JournaliserBCCree::class,
        ],
        \App\Domain\Approvisionnement\Events\BonCommandeValide::class => [
            \App\Listeners\Approvisionnement\GenererPdfBonCommande::class,
        ],
        \App\Domain\Approvisionnement\Events\LivraisonReceptionnee::class => [
            \App\Listeners\Approvisionnement\CreerMouvementsEntree::class,
            \App\Listeners\Approvisionnement\MettreAJourStatutBC::class,
            \App\Listeners\Approvisionnement\RecalculerCmup::class,
        ],

        // ============ STOCK ============
        \App\Domain\Approvisionnement\Events\SortieStockEnregistree::class => [
            \App\Listeners\Stock\ImputerCoutMatiere::class,
        ],
        \App\Domain\Approvisionnement\Events\StockSousSeuil::class => [
            \App\Listeners\Stock\NotifierResponsableAchats::class,
        ],
        \App\Domain\Approvisionnement\Events\StockRupture::class => [
            \App\Listeners\Stock\NotifierRuptureUrgente::class,
        ],
        \App\Domain\Approvisionnement\Events\InventaireValide::class => [
            \App\Listeners\Stock\JournaliserInventaireValide::class,
        ],

        // ============ MATÉRIEL ============
        \App\Domain\ParcMateriel\Events\EquipementAffecte::class => [
            \App\Listeners\Materiel\JournaliserAffectation::class,
        ],
        \App\Domain\ParcMateriel\Events\PanneDeclaree::class => [
            \App\Listeners\Materiel\ChangerStatutEquipementEnPanne::class,
            \App\Listeners\Materiel\NotifierResponsableMateriel::class,
        ],
        \App\Domain\ParcMateriel\Events\DocumentBientotExpire::class => [
            \App\Listeners\Materiel\NotifierDocumentExpirant::class,
        ],

        // ============ PERSONNEL ============
        \App\Domain\Personnel\Events\EmployeCree::class => [
            \App\Listeners\Personnel\JournaliserEmployeCree::class,
        ],
        \App\Domain\Personnel\Events\ContratExpireBientot::class => [
            \App\Listeners\Personnel\NotifierContratExpirant::class,
        ],
        \App\Domain\Personnel\Events\CongeDemande::class => [
            \App\Listeners\Personnel\NotifierDemandeConge::class,
        ],
        \App\Domain\Personnel\Events\CongeApprouve::class => [
            \App\Listeners\Personnel\NotifierCongeApprouve::class,
        ],
        \App\Domain\Personnel\Events\PresencesValidees::class => [
            \App\Listeners\Personnel\JournaliserPresencesValidees::class,
        ],
        \App\Domain\Personnel\Events\BulletinsGeneres::class => [
            \App\Listeners\Personnel\NotifierBulletinsGeneres::class,
        ],
        \App\Domain\Personnel\Events\PeriodePaieCloturee::class => [
            \App\Listeners\Personnel\GenererEcrituresSalaires::class,
            \App\Listeners\Personnel\ImputerMainOeuvreChantiers::class,
            \App\Listeners\Personnel\NotifierPeriodeCloturee::class,
        ],
        \App\Domain\Personnel\Events\HabilitationExpire::class => [
            \App\Listeners\Personnel\NotifierHabilitationExpirant::class,
        ],

        // ============ SOUS-TRAITANCE ============
        \App\Domain\SousTraitance\Events\SoustraitantBlackliste::class => [
            \App\Listeners\SousTraitance\NotifierSoustraitantBlackliste::class,
        ],
        \App\Domain\SousTraitance\Events\ContratSousTraitantCree::class => [
            \App\Listeners\SousTraitance\JournaliserContratCree::class,
        ],
        \App\Domain\SousTraitance\Events\PaiementSousTraitantEnregistre::class => [
            \App\Listeners\SousTraitance\VerifierPlafondPaiement::class,
        ],
        \App\Domain\SousTraitance\Events\PaiementPlafondAtteint::class => [
            \App\Listeners\SousTraitance\NotifierPlafondAtteint::class,
        ],

        // ============ FINANCES ============
        \App\Domain\Finances\Events\FactureEmise::class => [
            \App\Listeners\Finances\EnvoyerFactureParEmail::class,
            \App\Listeners\Finances\GenererPdfFacture::class,
        ],
        \App\Domain\Finances\Events\FactureEchue::class => [
            \App\Listeners\Finances\NotifierFactureEchue::class,
        ],
        \App\Domain\Finances\Events\PaiementEnregistre::class => [
            \App\Listeners\Finances\MettreAJourSoldeCaisseOuBanque::class,
            \App\Listeners\Finances\NotifierPaiementEnregistre::class,
        ],
        \App\Domain\Finances\Events\DepenseApprouvee::class => [
            \App\Listeners\Finances\ImputerDepenseAuBudget::class,
        ],
        \App\Domain\Finances\Events\EcritureValidee::class => [
            \App\Listeners\Finances\JournaliserEcritureValidee::class,
        ],
        \App\Domain\Finances\Events\ExerciceCloture::class => [
            \App\Listeners\Finances\NotifierExerciceCloture::class,
        ],

        // ============ QHSE ============
        \App\Domain\QHSE\Events\IncidentDeclare::class => [
            \App\Listeners\Qhse\JournaliserIncident::class,
        ],
        \App\Domain\QHSE\Events\IncidentGraveDeclare::class => [
            \App\Listeners\Qhse\NotifierDirectionUrgence::class,
            \App\Listeners\Qhse\NotifierResponsableQhse::class,
        ],
        \App\Domain\QHSE\Events\ReceptionDefinitiveSignee::class => [
            \App\Listeners\Qhse\LibererRetenuesGarantie::class,
            \App\Listeners\Qhse\NotifierReceptionDefinitive::class,
        ],
        \App\Domain\QHSE\Events\ReserveOuverte::class => [
            \App\Listeners\Qhse\NotifierReserveOuverte::class,
        ],
        \App\Domain\QHSE\Events\ReserveLevee::class => [
            \App\Listeners\Qhse\VerifierToutesReservesLevees::class,
        ],
    ];

    protected $subscribe = [];

    public function boot(): void
    {
        parent::boot();
    }
}