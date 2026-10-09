<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Toutes les liaisons Interface → Implémentation Eloquent.
     */
    public array $bindings = [
        // ==================== SOCLE ====================
        \App\Domain\Socle\Repositories\UserRepositoryInterface::class
            => \App\Domain\Socle\Repositories\EloquentUserRepository::class,
        \App\Domain\Socle\Repositories\RoleRepositoryInterface::class
            => \App\Domain\Socle\Repositories\EloquentRoleRepository::class,
        \App\Domain\Socle\Repositories\ParametreRepositoryInterface::class
            => \App\Domain\Socle\Repositories\EloquentParametreRepository::class,
        \App\Domain\Socle\Repositories\JournalRepositoryInterface::class
            => \App\Domain\Socle\Repositories\EloquentJournalRepository::class,
        \App\Domain\Socle\Repositories\CommunicationRepositoryInterface::class
            => \App\Domain\Socle\Repositories\EloquentCommunicationRepository::class,

        // ==================== COMMERCIAL ====================
        \App\Domain\Commercial\Repositories\ClientRepositoryInterface::class
            => \App\Domain\Commercial\Repositories\EloquentClientRepository::class,
        \App\Domain\Commercial\Repositories\DevisRepositoryInterface::class
            => \App\Domain\Commercial\Repositories\EloquentDevisRepository::class,
        \App\Domain\Commercial\Repositories\MarcheRepositoryInterface::class
            => \App\Domain\Commercial\Repositories\EloquentMarcheRepository::class,
        \App\Domain\Commercial\Repositories\AvenantMarcheRepositoryInterface::class
            => \App\Domain\Commercial\Repositories\EloquentAvenantMarcheRepository::class,
        \App\Domain\Commercial\Repositories\CautionMarcheRepositoryInterface::class
            => \App\Domain\Commercial\Repositories\EloquentCautionMarcheRepository::class,

        // ==================== EXÉCUTION ====================
        \App\Domain\Execution\Repositories\ProjetRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentProjetRepository::class,
        \App\Domain\Execution\Repositories\PhaseRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentPhaseRepository::class,
        \App\Domain\Execution\Repositories\TacheRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentTacheRepository::class,
        \App\Domain\Execution\Repositories\JalonRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentJalonRepository::class,
        \App\Domain\Execution\Repositories\AttachementRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentAttachementRepository::class,
        \App\Domain\Execution\Repositories\SituationRepositoryInterface::class
            => \App\Domain\Execution\Repositories\EloquentSituationRepository::class,

        // ==================== APPROVISIONNEMENT ====================
        \App\Domain\Approvisionnement\Repositories\FournisseurRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentFournisseurRepository::class,
        \App\Domain\Approvisionnement\Repositories\MateriauRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentMateriauRepository::class,
        \App\Domain\Approvisionnement\Repositories\StockRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentStockRepository::class,
        \App\Domain\Approvisionnement\Repositories\BonCommandeRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentBonCommandeRepository::class,
        \App\Domain\Approvisionnement\Repositories\LivraisonRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentLivraisonRepository::class,
        \App\Domain\Approvisionnement\Repositories\DemandeAchatRepositoryInterface::class
            => \App\Domain\Approvisionnement\Repositories\EloquentDemandeAchatRepository::class,

        // ==================== PARC MATÉRIEL ====================
        \App\Domain\ParcMateriel\Repositories\EquipementRepositoryInterface::class
            => \App\Domain\ParcMateriel\Repositories\EloquentEquipementRepository::class,
        \App\Domain\ParcMateriel\Repositories\PanneRepositoryInterface::class
            => \App\Domain\ParcMateriel\Repositories\EloquentPanneRepository::class,
        \App\Domain\ParcMateriel\Repositories\MaintenanceRepositoryInterface::class
            => \App\Domain\ParcMateriel\Repositories\EloquentMaintenanceRepository::class,

        // ==================== PERSONNEL ====================
        \App\Domain\Personnel\Repositories\EmployeRepositoryInterface::class
            => \App\Domain\Personnel\Repositories\EloquentEmployeRepository::class,
        \App\Domain\Personnel\Repositories\ContratRepositoryInterface::class
            => \App\Domain\Personnel\Repositories\EloquentContratRepository::class,
        \App\Domain\Personnel\Repositories\PresenceRepositoryInterface::class
            => \App\Domain\Personnel\Repositories\EloquentPresenceRepository::class,
        \App\Domain\Personnel\Repositories\PeriodePaieRepositoryInterface::class
            => \App\Domain\Personnel\Repositories\EloquentPeriodePaieRepository::class,
        \App\Domain\Personnel\Repositories\BulletinPaieRepositoryInterface::class
            => \App\Domain\Personnel\Repositories\EloquentBulletinPaieRepository::class,

        // ==================== SOUS-TRAITANCE ====================
        \App\Domain\SousTraitance\Repositories\SoustraitantRepositoryInterface::class
            => \App\Domain\SousTraitance\Repositories\EloquentSoustraitantRepository::class,
        \App\Domain\SousTraitance\Repositories\ContratSousTraitantRepositoryInterface::class
            => \App\Domain\SousTraitance\Repositories\EloquentContratSousTraitantRepository::class,

        // ==================== FINANCES ====================
        \App\Domain\Finances\Repositories\FactureRepositoryInterface::class
            => \App\Domain\Finances\Repositories\EloquentFactureRepository::class,
        \App\Domain\Finances\Repositories\PaiementRepositoryInterface::class
            => \App\Domain\Finances\Repositories\EloquentPaiementRepository::class,
        \App\Domain\Finances\Repositories\DepenseRepositoryInterface::class
            => \App\Domain\Finances\Repositories\EloquentDepenseRepository::class,
        \App\Domain\Finances\Repositories\EcritureComptableRepositoryInterface::class
            => \App\Domain\Finances\Repositories\EloquentEcritureComptableRepository::class,

        // ==================== QHSE ====================
        \App\Domain\QHSE\Repositories\IncidentRepositoryInterface::class
            => \App\Domain\QHSE\Repositories\EloquentIncidentRepository::class,
        \App\Domain\QHSE\Repositories\PvReceptionRepositoryInterface::class
            => \App\Domain\QHSE\Repositories\EloquentPvReceptionRepository::class,

             // Mais on peut forcer le singleton si un service maintient un état :


    ];
}