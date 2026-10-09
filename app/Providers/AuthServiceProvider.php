<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapping Model → Policy.
     */
    protected $policies = [
        // ============ Socle ============
        \App\Domain\Socle\Models\User::class                    => \App\Policies\Socle\UserPolicy::class,
        \App\Domain\Socle\Models\Role::class                    => \App\Policies\Socle\RolePolicy::class,
        \App\Domain\Socle\Models\Permission::class              => \App\Policies\Socle\PermissionPolicy::class,
        \App\Domain\Socle\Models\Parametre::class               => \App\Policies\Socle\ParametrePolicy::class,
        \App\Domain\Socle\Models\Communication::class           => \App\Policies\Socle\CommunicationPolicy::class,
        \App\Domain\Socle\Models\CommunicationTemplate::class   => \App\Policies\Socle\CommunicationTemplatePolicy::class,

        // ============ Commercial ============
        \App\Domain\Commercial\Models\Client::class             => \App\Policies\Commercial\ClientPolicy::class,
        \App\Domain\Commercial\Models\Devis::class              => \App\Policies\Commercial\DevisPolicy::class,
        \App\Domain\Commercial\Models\Marche::class             => \App\Policies\Commercial\MarchePolicy::class,
        \App\Domain\Commercial\Models\AvenantMarche::class      => \App\Policies\Commercial\AvenantMarchePolicy::class,
        \App\Domain\Commercial\Models\CautionMarche::class      => \App\Policies\Commercial\CautionMarchePolicy::class,

        // ============ Exécution ============
        \App\Domain\Execution\Models\Projet::class              => \App\Policies\Execution\ProjetPolicy::class,
        \App\Domain\Execution\Models\PhaseProjet::class         => \App\Policies\Execution\PhaseProjetPolicy::class,
        \App\Domain\Execution\Models\Tache::class               => \App\Policies\Execution\TachePolicy::class,
        \App\Domain\Execution\Models\DependanceTache::class     => \App\Policies\Execution\DependanceTachePolicy::class,
        \App\Domain\Execution\Models\JalonProjet::class         => \App\Policies\Execution\JalonProjetPolicy::class,
        \App\Domain\Execution\Models\LigneBudget::class         => \App\Policies\Execution\LigneBudgetPolicy::class,
        \App\Domain\Execution\Models\AvancementProjet::class    => \App\Policies\Execution\AvancementProjetPolicy::class,
        \App\Domain\Execution\Models\EquipeProjet::class        => \App\Policies\Execution\EquipeProjetPolicy::class,

        // ============ Situation ============
        \App\Domain\Execution\Models\Attachement::class         => \App\Policies\Situation\AttachementPolicy::class,
        \App\Domain\Execution\Models\LigneAttachement::class    => \App\Policies\Situation\LigneAttachementPolicy::class,
        \App\Domain\Execution\Models\Situation::class           => \App\Policies\Situation\SituationPolicy::class,

        // ============ Approvisionnement ============
        \App\Domain\Approvisionnement\Models\Fournisseur::class            => \App\Policies\Approvisionnement\FournisseurPolicy::class,
        \App\Domain\Approvisionnement\Models\DemandeAchat::class           => \App\Policies\Approvisionnement\DemandeAchatPolicy::class,
        \App\Domain\Approvisionnement\Models\ArticleDemandeAchat::class    => \App\Policies\Approvisionnement\ArticleDemandeAchatPolicy::class,
        \App\Domain\Approvisionnement\Models\BonCommande::class            => \App\Policies\Approvisionnement\BonCommandePolicy::class,
        \App\Domain\Approvisionnement\Models\ArticleBonCommande::class     => \App\Policies\Approvisionnement\ArticleBonCommandePolicy::class,
        \App\Domain\Approvisionnement\Models\Livraison::class              => \App\Policies\Approvisionnement\LivraisonPolicy::class,

        // ============ Stock ============
        \App\Domain\Approvisionnement\Models\CategorieMateriau::class      => \App\Policies\Stock\CategorieMateriauPolicy::class,
        \App\Domain\Approvisionnement\Models\Materiau::class               => \App\Policies\Stock\MateriauPolicy::class,
        \App\Domain\Approvisionnement\Models\Entrepot::class               => \App\Policies\Stock\EntrepotPolicy::class,
        \App\Domain\Approvisionnement\Models\NiveauStock::class            => \App\Policies\Stock\NiveauStockPolicy::class,
        \App\Domain\Approvisionnement\Models\MouvementStock::class         => \App\Policies\Stock\MouvementStockPolicy::class,
        \App\Domain\Approvisionnement\Models\TransfertStock::class         => \App\Policies\Stock\TransfertStockPolicy::class,
        \App\Domain\Approvisionnement\Models\Inventaire::class             => \App\Policies\Stock\InventairePolicy::class,
        \App\Domain\Approvisionnement\Models\ArticleInventaire::class      => \App\Policies\Stock\ArticleInventairePolicy::class,

        // ============ Matériel ============
        \App\Domain\ParcMateriel\Models\CategorieEquipement::class    => \App\Policies\Materiel\CategorieEquipementPolicy::class,
        \App\Domain\ParcMateriel\Models\Equipement::class             => \App\Policies\Materiel\EquipementPolicy::class,
        \App\Domain\ParcMateriel\Models\AffectationEquipement::class  => \App\Policies\Materiel\AffectationEquipementPolicy::class,
        \App\Domain\ParcMateriel\Models\MaintenanceEquipement::class  => \App\Policies\Materiel\MaintenanceEquipementPolicy::class,
        \App\Domain\ParcMateriel\Models\PanneEquipement::class        => \App\Policies\Materiel\PanneEquipementPolicy::class,
        \App\Domain\ParcMateriel\Models\ReleveCarburant::class        => \App\Policies\Materiel\ReleveCarburantPolicy::class,
        \App\Domain\ParcMateriel\Models\DocumentEquipement::class     => \App\Policies\Materiel\DocumentEquipementPolicy::class,

        // ============ Personnel ============
        \App\Domain\Personnel\Models\Departement::class      => \App\Policies\Personnel\DepartementPolicy::class,
        \App\Domain\Personnel\Models\Poste::class            => \App\Policies\Personnel\PostePolicy::class,
        \App\Domain\Personnel\Models\Employe::class          => \App\Policies\Personnel\EmployePolicy::class,
        \App\Domain\Personnel\Models\Contrat::class          => \App\Policies\Personnel\ContratPolicy::class,
        \App\Domain\Personnel\Models\TypeConge::class        => \App\Policies\Personnel\TypeCongePolicy::class,
        \App\Domain\Personnel\Models\DemandeConge::class     => \App\Policies\Personnel\DemandeCongePolicy::class,
        \App\Domain\Personnel\Models\DocumentEmploye::class  => \App\Policies\Personnel\DocumentEmployePolicy::class,
        \App\Domain\Personnel\Models\Presence::class         => \App\Policies\Personnel\PresencePolicy::class,
        \App\Domain\Personnel\Models\PeriodePaie::class      => \App\Policies\Personnel\PeriodePaiePolicy::class,
        \App\Domain\Personnel\Models\BulletinPaie::class     => \App\Policies\Personnel\BulletinPaiePolicy::class,
        \App\Domain\Personnel\Models\AvanceSalaire::class    => \App\Policies\Personnel\AvanceSalairePolicy::class,

        // ============ Sous-traitance ============
        \App\Domain\SousTraitance\Models\Soustraitant::class             => \App\Policies\SousTraitance\SoustraitantPolicy::class,
        \App\Domain\SousTraitance\Models\ContratSousTraitant::class      => \App\Policies\SousTraitance\ContratSousTraitantPolicy::class,
        \App\Domain\SousTraitance\Models\FactureSousTraitant::class      => \App\Policies\SousTraitance\FactureSousTraitantPolicy::class,
        \App\Domain\SousTraitance\Models\PaiementSousTraitant::class     => \App\Policies\SousTraitance\PaiementSousTraitantPolicy::class,
        \App\Domain\SousTraitance\Models\EvaluationSousTraitant::class   => \App\Policies\SousTraitance\EvaluationSousTraitantPolicy::class,

        // ============ Finances ============
        \App\Domain\Finances\Models\PlanComptable::class              => \App\Policies\Finances\PlanComptablePolicy::class,
        \App\Domain\Finances\Models\ExerciceFiscal::class             => \App\Policies\Finances\ExerciceFiscalPolicy::class,
        \App\Domain\Finances\Models\EcritureComptable::class          => \App\Policies\Finances\EcritureComptablePolicy::class,
        \App\Domain\Finances\Models\LigneEcritureComptable::class     => \App\Policies\Finances\LigneEcritureComptablePolicy::class,
        \App\Domain\Finances\Models\CompteBancaire::class             => \App\Policies\Finances\CompteBancairePolicy::class,
        \App\Domain\Finances\Models\Caisse::class                     => \App\Policies\Finances\CaissePolicy::class,
        \App\Domain\Finances\Models\Facture::class                    => \App\Policies\Finances\FacturePolicy::class,
        \App\Domain\Finances\Models\Paiement::class                   => \App\Policies\Finances\PaiementPolicy::class,
        \App\Domain\Finances\Models\Depense::class                    => \App\Policies\Finances\DepensePolicy::class,

        // ============ QHSE ============
        \App\Domain\QHSE\Models\IncidentSecurite::class   => \App\Policies\Qhse\IncidentSecuritePolicy::class,
        \App\Domain\QHSE\Models\CauserieSecurite::class   => \App\Policies\Qhse\CauserieSecuritePolicy::class,
        \App\Domain\QHSE\Models\NonConformite::class      => \App\Policies\Qhse\NonConformitePolicy::class,
        \App\Domain\QHSE\Models\PvReception::class        => \App\Policies\Qhse\PvReceptionPolicy::class,
        \App\Domain\QHSE\Models\Reserve::class            => \App\Policies\Qhse\ReservePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}