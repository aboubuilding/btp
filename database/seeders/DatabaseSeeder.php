<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Vider les tables
        $this->truncateTables();

        // 1. Rôles
        $roles = $this->seedRoles();

        // 2. Utilisateurs
        $this->seedUsers($roles);

        // 3. Départements
        $departements = $this->seedDepartements();

        // 4. Postes
        $postes = $this->seedPostes($departements);

        // 5. Employés
        $employes = $this->seedEmployes($departements, $postes);

        // 6. Clients
        $clients = $this->seedClients();

        // 7. Projets
        $projets = $this->seedProjets($clients, $employes);

        // 8. Phases de projet
        $phases = $this->seedPhaseProjets($projets);

        // 9. Tâches
        $this->seedTaches($projets, $phases, $employes);

        // 10. Équipe projet
        $this->seedEquipeProjets($projets, $employes);

        // 11. Jalons
        $this->seedJalonProjets($projets);

        // 12. Équipements
        $this->seedEquipements();

        // 13. Fournisseurs
        $this->seedFournisseurs();

        // 14. Catégories matériaux
        $categories = $this->seedCategorieMateriaus();

        // 15. Matériaux
        $materiaux = $this->seedMateriaus($categories);

        // 16. Entrepôts
        $entrepots = $this->seedEntrepots($projets);

        // 17. Niveaux de stock
        $this->seedNiveauStocks($entrepots, $materiaux);

        // 18. Types de congés
        $this->seedTypeConges();

        // 19. Plan comptable
        $this->seedPlanComptables();

        // 20. Périodes de paie
        $this->seedPeriodePaies();

        // 21. Sous-traitants
        $this->seedSoustraitants();

        // 22. Comptes bancaires
        $this->seedCompteBancaires();

        // 23. Caisses
        $this->seedCaisses();

        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✅ Base de données initialisée avec succès !');
        $this->command->info('📋 Identifiants de connexion :');
        $this->command->info('   Admin: admin@btp.com / Admin123!');
        $this->command->info('   Direction: direction@btp.com / Direction123!');
        $this->command->info('   Conducteur: conducteur@btp.com / Conducteur123!');
        $this->command->info('   Chef chantier: chef@btp.com / Chef123!');
        $this->command->info('   Achats: achats@btp.com / Achats123!');
        $this->command->info('   Comptable: comptable@btp.com / Comptable123!');
        $this->command->info('   RH: rh@btp.com / Rh123!');
    }

    /**
     * Tronquer toutes les tables
     */
    private function truncateTables(): void
    {
        $tables = [
            'roles', 'users', 'departements', 'postes', 'employes',
            'clients', 'projets', 'phase_projets', 'taches', 'equipe_projets',
            'jalon_projets', 'categorie_equipements', 'equipements',
            'fournisseurs', 'categorie_materiaus', 'materiaus',
            'entrepots', 'niveau_stocks', 'type_conges',
            'plan_comptables', 'periode_paies', 'soustraitants',
            'compte_bancaires', 'caisses'
        ];

        foreach ($tables as $table) {
            if (DB::table($table)->exists()) {
                DB::table($table)->truncate();
            }
        }
    }

    /**
     * Seed les rôles
     */
    private function seedRoles(): array
    {
        $roles = [
            ['nom' => 'Administrateur', 'slug' => 'admin', 'description' => 'Administrateur système', 'etat' => 1],
            ['nom' => 'Direction', 'slug' => 'direction', 'description' => 'Direction générale', 'etat' => 1],
            ['nom' => 'Conducteur de travaux', 'slug' => 'conducteur_travaux', 'description' => 'Conducteur de travaux', 'etat' => 1],
            ['nom' => 'Chef de chantier', 'slug' => 'chef_chantier', 'description' => 'Chef de chantier', 'etat' => 1],
            ['nom' => 'Responsable achats', 'slug' => 'responsable_achat', 'description' => 'Responsable des achats', 'etat' => 1],
            ['nom' => 'Comptable', 'slug' => 'comptable', 'description' => 'Comptable', 'etat' => 1],
            ['nom' => 'RH', 'slug' => 'rh', 'description' => 'Ressources Humaines', 'etat' => 1],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'nom' => $role['nom'],
                'slug' => $role['slug'],
                'description' => $role['description'],
                'etat' => $role['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('roles')->pluck('id', 'slug')->toArray();
    }

    /**
     * Seed les utilisateurs
     */
    private function seedUsers(array $roles): void
    {
        $users = [
            [
                'nom' => 'Admin BTP',
                'email' => 'admin@btp.com',
                'telephone' => '+228 90000001',
                'role_slug' => 'admin',
                'mot_de_passe' => 'Admin123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Directeur Général',
                'email' => 'direction@btp.com',
                'telephone' => '+228 90000002',
                'role_slug' => 'direction',
                'mot_de_passe' => 'Direction123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Jean Conducteur',
                'email' => 'conducteur@btp.com',
                'telephone' => '+228 90000003',
                'role_slug' => 'conducteur_travaux',
                'mot_de_passe' => 'Conducteur123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Pierre Chef',
                'email' => 'chef@btp.com',
                'telephone' => '+228 90000004',
                'role_slug' => 'chef_chantier',
                'mot_de_passe' => 'Chef123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Marie Achats',
                'email' => 'achats@btp.com',
                'telephone' => '+228 90000005',
                'role_slug' => 'responsable_achat',
                'mot_de_passe' => 'Achats123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Paul Comptable',
                'email' => 'comptable@btp.com',
                'telephone' => '+228 90000006',
                'role_slug' => 'comptable',
                'mot_de_passe' => 'Comptable123!',
                'est_actif' => true,
                'etat' => 1
            ],
            [
                'nom' => 'Sophie RH',
                'email' => 'rh@btp.com',
                'telephone' => '+228 90000007',
                'role_slug' => 'rh',
                'mot_de_passe' => 'Rh123!',
                'est_actif' => true,
                'etat' => 1
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'role_id' => $roles[$user['role_slug']],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'telephone' => $user['telephone'],
                'mot_de_passe' => Hash::make($user['mot_de_passe']),
                'est_actif' => $user['est_actif'],
                'email_verifie_le' => now(),
                'etat' => $user['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les départements
     */
    private function seedDepartements(): array
    {
        $departements = [
            ['nom' => 'Direction Générale', 'code' => 'DG', 'etat' => 1],
            ['nom' => 'Conduite de Travaux', 'code' => 'CT', 'etat' => 1],
            ['nom' => 'Chantier', 'code' => 'CH', 'etat' => 1],
            ['nom' => 'Achats', 'code' => 'ACH', 'etat' => 1],
            ['nom' => 'Comptabilité', 'code' => 'CMP', 'etat' => 1],
            ['nom' => 'Ressources Humaines', 'code' => 'RH', 'etat' => 1],
            ['nom' => 'Logistique', 'code' => 'LOG', 'etat' => 1],
            ['nom' => 'Sécurité', 'code' => 'SEC', 'etat' => 1],
        ];

        foreach ($departements as $dept) {
            DB::table('departements')->insert([
                'nom' => $dept['nom'],
                'code' => $dept['code'],
                'etat' => $dept['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('departements')->pluck('id', 'code')->toArray();
    }

    /**
     * Seed les postes
     */
    private function seedPostes(array $departements): array
    {
        $postes = [
            ['intitule' => 'Directeur Général', 'departement_code' => 'DG', 'etat' => 1],
            ['intitule' => 'Conducteur de Travaux Senior', 'departement_code' => 'CT', 'etat' => 1],
            ['intitule' => 'Conducteur de Travaux', 'departement_code' => 'CT', 'etat' => 1],
            ['intitule' => 'Chef de Chantier', 'departement_code' => 'CH', 'etat' => 1],
            ['intitule' => 'Maçon', 'departement_code' => 'CH', 'etat' => 1],
            ['intitule' => 'Ferailleur', 'departement_code' => 'CH', 'etat' => 1],
            ['intitule' => 'Chauffeur Engin', 'departement_code' => 'CH', 'etat' => 1],
            ['intitule' => 'Responsable Achats', 'departement_code' => 'ACH', 'etat' => 1],
            ['intitule' => 'Acheteur', 'departement_code' => 'ACH', 'etat' => 1],
            ['intitule' => 'Comptable', 'departement_code' => 'CMP', 'etat' => 1],
            ['intitule' => 'Assistant Comptable', 'departement_code' => 'CMP', 'etat' => 1],
            ['intitule' => 'Responsable RH', 'departement_code' => 'RH', 'etat' => 1],
            ['intitule' => 'Assistant RH', 'departement_code' => 'RH', 'etat' => 1],
            ['intitule' => 'Magasinier', 'departement_code' => 'LOG', 'etat' => 1],
            ['intitule' => 'Chauffeur', 'departement_code' => 'LOG', 'etat' => 1],
            ['intitule' => 'Agent de Sécurité', 'departement_code' => 'SEC', 'etat' => 1],
        ];

        foreach ($postes as $poste) {
            DB::table('postes')->insert([
                'departement_id' => $departements[$poste['departement_code']],
                'intitule' => $poste['intitule'],
                'etat' => $poste['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('postes')->pluck('id', 'intitule')->toArray();
    }

    /**
     * Seed les employés
     */
    private function seedEmployes(array $departements, array $postes): array
    {
        $employes = [
            [
                'matricule' => 'EMP001',
                'prenom' => 'Jean',
                'nom' => 'Dupont',
                'genre' => 'homme',
                'date_naissance' => '1980-05-15',
                'cin' => 'TGO123456',
                'numero_cnss' => 'CNSS001',
                'date_embauche' => '2015-01-01',
                'departement_code' => 'DG',
                'poste_intitule' => 'Directeur Général',
                'type_contrat' => 'cdi',
                'salaire_base' => 1500000,
                'telephone' => '+228 91111111',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP002',
                'prenom' => 'Marie',
                'nom' => 'Dossou',
                'genre' => 'femme',
                'date_naissance' => '1985-08-20',
                'cin' => 'TGO123457',
                'numero_cnss' => 'CNSS002',
                'date_embauche' => '2016-03-15',
                'departement_code' => 'CT',
                'poste_intitule' => 'Conducteur de Travaux Senior',
                'type_contrat' => 'cdi',
                'salaire_base' => 1200000,
                'telephone' => '+228 91111112',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP003',
                'prenom' => 'Pierre',
                'nom' => 'Koffi',
                'genre' => 'homme',
                'date_naissance' => '1990-12-10',
                'cin' => 'TGO123458',
                'numero_cnss' => 'CNSS003',
                'date_embauche' => '2017-06-01',
                'departement_code' => 'CH',
                'poste_intitule' => 'Chef de Chantier',
                'type_contrat' => 'cdi',
                'salaire_base' => 900000,
                'telephone' => '+228 91111113',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP004',
                'prenom' => 'Amadou',
                'nom' => 'Traoré',
                'genre' => 'homme',
                'date_naissance' => '1995-03-25',
                'cin' => 'TGO123459',
                'numero_cnss' => 'CNSS004',
                'date_embauche' => '2019-08-15',
                'departement_code' => 'CH',
                'poste_intitule' => 'Maçon',
                'type_contrat' => 'cdd',
                'salaire_base' => 250000,
                'telephone' => '+228 91111114',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP005',
                'prenom' => 'Koffi',
                'nom' => 'Mensah',
                'genre' => 'homme',
                'date_naissance' => '1992-07-14',
                'cin' => 'TGO123460',
                'numero_cnss' => 'CNSS005',
                'date_embauche' => '2020-01-10',
                'departement_code' => 'CH',
                'poste_intitule' => 'Chauffeur Engin',
                'type_contrat' => 'cdi',
                'salaire_base' => 300000,
                'telephone' => '+228 91111115',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP006',
                'prenom' => 'Sophie',
                'nom' => 'Adjei',
                'genre' => 'femme',
                'date_naissance' => '1988-09-30',
                'cin' => 'TGO123461',
                'numero_cnss' => 'CNSS006',
                'date_embauche' => '2018-04-01',
                'departement_code' => 'ACH',
                'poste_intitule' => 'Responsable Achats',
                'type_contrat' => 'cdi',
                'salaire_base' => 800000,
                'telephone' => '+228 91111116',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP007',
                'prenom' => 'Paul',
                'nom' => 'Yao',
                'genre' => 'homme',
                'date_naissance' => '1978-11-05',
                'cin' => 'TGO123462',
                'numero_cnss' => 'CNSS007',
                'date_embauche' => '2010-07-01',
                'departement_code' => 'CMP',
                'poste_intitule' => 'Comptable',
                'type_contrat' => 'cdi',
                'salaire_base' => 750000,
                'telephone' => '+228 91111117',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP008',
                'prenom' => 'Fatou',
                'nom' => 'Diouf',
                'genre' => 'femme',
                'date_naissance' => '1993-02-18',
                'cin' => 'TGO123463',
                'numero_cnss' => 'CNSS008',
                'date_embauche' => '2019-09-01',
                'departement_code' => 'RH',
                'poste_intitule' => 'Responsable RH',
                'type_contrat' => 'cdi',
                'salaire_base' => 700000,
                'telephone' => '+228 91111118',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP009',
                'prenom' => 'Mamadou',
                'nom' => 'Diallo',
                'genre' => 'homme',
                'date_naissance' => '1998-06-12',
                'cin' => 'TGO123464',
                'numero_cnss' => 'CNSS009',
                'date_embauche' => '2021-03-01',
                'departement_code' => 'CH',
                'poste_intitule' => 'Ferailleur',
                'type_contrat' => 'cdd',
                'salaire_base' => 200000,
                'telephone' => '+228 91111119',
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'matricule' => 'EMP010',
                'prenom' => 'Awa',
                'nom' => 'Ndiaye',
                'genre' => 'femme',
                'date_naissance' => '1996-09-08',
                'cin' => 'TGO123465',
                'numero_cnss' => 'CNSS010',
                'date_embauche' => '2020-11-15',
                'departement_code' => 'LOG',
                'poste_intitule' => 'Magasinier',
                'type_contrat' => 'cdi',
                'salaire_base' => 350000,
                'telephone' => '+228 91111120',
                'statut' => 'actif',
                'etat' => 1
            ]
        ];

        $insertedIds = [];
        foreach ($employes as $employe) {
            $id = DB::table('employes')->insertGetId([
                'user_id' => null,
                'matricule' => $employe['matricule'],
                'prenom' => $employe['prenom'],
                'nom' => $employe['nom'],
                'genre' => $employe['genre'],
                'date_naissance' => $employe['date_naissance'],
                'cin' => $employe['cin'],
                'numero_cnss' => $employe['numero_cnss'],
                'date_embauche' => $employe['date_embauche'],
                'departement_id' => $departements[$employe['departement_code']],
                'poste_id' => $postes[$employe['poste_intitule']],
                'type_contrat' => $employe['type_contrat'],
                'salaire_base' => $employe['salaire_base'],
                'telephone' => $employe['telephone'],
                'statut' => $employe['statut'],
                'etat' => $employe['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedIds[$employe['matricule']] = $id;
        }

        return $insertedIds;
    }

    /**
     * Seed les clients
     */
    private function seedClients(): array
    {
        $clients = [
            [
                'nom' => 'BTP Moderne SARL',
                'type' => 'entreprise',
                'personne_contact' => 'M. K. Bamba',
                'telephone' => '+228 92222222',
                'email' => 'contact@btpmoderne.com',
                'adresse' => 'Lomé, Togo',
                'nif' => 'NIF12345',
                'etat' => 1
            ],
            [
                'nom' => 'M. Jean Koffi',
                'type' => 'particulier',
                'personne_contact' => 'Jean Koffi',
                'telephone' => '+228 93333333',
                'email' => 'jean.koffi@gmail.com',
                'adresse' => 'Sokodé, Togo',
                'nif' => null,
                'etat' => 1
            ],
            [
                'nom' => 'Ministère des Infrastructures',
                'type' => 'public',
                'personne_contact' => 'Dr. A. N\'dri',
                'telephone' => '+228 94444444',
                'email' => 'infrastructures@ministere.tg',
                'adresse' => 'Lomé, Togo',
                'nif' => 'NIF67890',
                'etat' => 1
            ],
            [
                'nom' => 'Construction Plus SA',
                'type' => 'entreprise',
                'personne_contact' => 'Mme C. Ehui',
                'telephone' => '+228 95555555',
                'email' => 'info@constructionplus.tg',
                'adresse' => 'Kara, Togo',
                'nif' => 'NIF24680',
                'etat' => 1
            ]
        ];

        foreach ($clients as $client) {
            DB::table('clients')->insert([
                'nom' => $client['nom'],
                'type' => $client['type'],
                'personne_contact' => $client['personne_contact'],
                'telephone' => $client['telephone'],
                'email' => $client['email'],
                'adresse' => $client['adresse'],
                'nif' => $client['nif'],
                'etat' => $client['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('clients')->pluck('id', 'nom')->toArray();
    }

    /**
     * Seed les projets
     */
    private function seedProjets(array $clients, array $employes): array
    {
        $projets = [
            [
                'code' => 'PRJ-2026-001',
                'nom' => 'Construction Immeuble R+5 Lomé',
                'client_nom' => 'BTP Moderne SARL',
                'adresse' => 'Quartier du Lac, Lomé, Togo',
                'ville' => 'Lomé',
                'type' => 'batiment',
                'conducteur_matricule' => 'EMP002',
                'chef_matricule' => 'EMP003',
                'date_debut_prevue' => '2026-01-15',
                'date_fin_prevue' => '2026-12-15',
                'budget_prevue' => 500000000,
                'montant_contrat' => 450000000,
                'statut' => 'en_cours',
                'etat' => 1
            ],
            [
                'code' => 'PRJ-2026-002',
                'nom' => 'Terrassement Zone Industrielle',
                'client_nom' => 'M. Jean Koffi',
                'adresse' => 'Zone Industrielle, Lomé, Togo',
                'ville' => 'Lomé',
                'type' => 'terrassement',
                'conducteur_matricule' => 'EMP002',
                'chef_matricule' => 'EMP003',
                'date_debut_prevue' => '2026-02-01',
                'date_fin_prevue' => '2026-06-30',
                'budget_prevue' => 150000000,
                'montant_contrat' => 120000000,
                'statut' => 'planifie',
                'etat' => 1
            ],
            [
                'code' => 'PRJ-2026-003',
                'nom' => 'Construction Pont Kara',
                'client_nom' => 'Ministère des Infrastructures',
                'adresse' => 'Kara, Togo',
                'ville' => 'Kara',
                'type' => 'ouvrage_art',
                'conducteur_matricule' => 'EMP002',
                'chef_matricule' => 'EMP003',
                'date_debut_prevue' => '2026-03-01',
                'date_fin_prevue' => '2027-03-01',
                'budget_prevue' => 800000000,
                'montant_contrat' => 750000000,
                'statut' => 'planifie',
                'etat' => 1
            ]
        ];

        $insertedProjets = [];
        foreach ($projets as $projet) {
            $id = DB::table('projets')->insertGetId([
                'code' => $projet['code'],
                'nom' => $projet['nom'],
                'client_id' => $clients[$projet['client_nom']],
                'adresse' => $projet['adresse'],
                'ville' => $projet['ville'],
                'type' => $projet['type'],
                'conducteur_travaux_id' => $employes[$projet['conducteur_matricule']],
                'chef_chantier_id' => $employes[$projet['chef_matricule']],
                'date_debut_prevue' => $projet['date_debut_prevue'],
                'date_fin_prevue' => $projet['date_fin_prevue'],
                'date_debut_reelle' => $projet['statut'] === 'en_cours' ? $projet['date_debut_prevue'] : null,
                'budget_prevue' => $projet['budget_prevue'],
                'montant_contrat' => $projet['montant_contrat'],
                'pourcentage_avancement' => $projet['statut'] === 'en_cours' ? 25 : 0,
                'statut' => $projet['statut'],
                'etat' => $projet['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedProjets[$projet['code']] = $id;
        }

        return $insertedProjets;
    }

    /**
     * Seed les phases de projet
     */
    private function seedPhaseProjets(array $projets): array
    {
        $phases = [
            'PRJ-2026-001' => [
                ['nom' => 'Terrassement', 'ordre' => 1, 'statut' => 'termine', 'avancement' => 100],
                ['nom' => 'Fondations', 'ordre' => 2, 'statut' => 'termine', 'avancement' => 100],
                ['nom' => 'Gros œuvre', 'ordre' => 3, 'statut' => 'en_cours', 'avancement' => 50],
                ['nom' => 'Second œuvre', 'ordre' => 4, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Finitions', 'ordre' => 5, 'statut' => 'a_venir', 'avancement' => 0]
            ],
            'PRJ-2026-002' => [
                ['nom' => 'Déblaiement', 'ordre' => 1, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Terrassement', 'ordre' => 2, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Compactage', 'ordre' => 3, 'statut' => 'a_venir', 'avancement' => 0]
            ],
            'PRJ-2026-003' => [
                ['nom' => 'Études', 'ordre' => 1, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Terrassement', 'ordre' => 2, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Fondations', 'ordre' => 3, 'statut' => 'a_venir', 'avancement' => 0],
                ['nom' => 'Structure', 'ordre' => 4, 'statut' => 'a_venir', 'avancement' => 0]
            ]
        ];

        $insertedPhases = [];
        foreach ($phases as $projetCode => $phaseList) {
            foreach ($phaseList as $phase) {
                $id = DB::table('phase_projets')->insertGetId([
                    'projet_id' => $projets[$projetCode],
                    'nom' => $phase['nom'],
                    'ordre' => $phase['ordre'],
                    'date_debut' => $phase['statut'] !== 'a_venir' ? Carbon::now()->subDays(rand(1, 60)) : null,
                    'date_fin' => $phase['statut'] === 'termine' ? Carbon::now()->subDays(rand(1, 30)) : null,
                    'statut' => $phase['statut'],
                    'pourcentage_avancement' => $phase['avancement'],
                    'etat' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedPhases[$phase['nom'] . '_' . $projetCode] = $id;
            }
        }

        return $insertedPhases;
    }

    /**
     * Seed les tâches
     */
    private function seedTaches(array $projets, array $phases, array $employes): void
    {
        $taches = [
            'PRJ-2026-001' => [
                ['nom' => 'Terrassement du terrain', 'phase_nom' => 'Terrassement', 'statut' => 'termine', 'priorite' => 'haute', 'avancement' => 100],
                ['nom' => 'Coulage fondations', 'phase_nom' => 'Fondations', 'statut' => 'termine', 'priorite' => 'haute', 'avancement' => 100],
                ['nom' => 'Élévation mur R+1', 'phase_nom' => 'Gros œuvre', 'statut' => 'en_cours', 'priorite' => 'haute', 'avancement' => 60],
                ['nom' => 'Installation électricité', 'phase_nom' => 'Second œuvre', 'statut' => 'a_faire', 'priorite' => 'normale', 'avancement' => 0],
                ['nom' => 'Plomberie intérieure', 'phase_nom' => 'Second œuvre', 'statut' => 'a_faire', 'priorite' => 'normale', 'avancement' => 0]
            ],
            'PRJ-2026-002' => [
                ['nom' => 'Déblaiement du site', 'phase_nom' => 'Déblaiement', 'statut' => 'a_faire', 'priorite' => 'haute', 'avancement' => 0],
                ['nom' => 'Terrassement principal', 'phase_nom' => 'Terrassement', 'statut' => 'a_faire', 'priorite' => 'haute', 'avancement' => 0]
            ]
        ];

        foreach ($taches as $projetCode => $tacheList) {
            foreach ($tacheList as $tache) {
                DB::table('taches')->insert([
                    'projet_id' => $projets[$projetCode],
                    'phase_id' => $phases[$tache['phase_nom'] . '_' . $projetCode] ?? null,
                    'nom' => $tache['nom'],
                    'description' => 'Description de la tâche: ' . $tache['nom'],
                    'assigne_a' => $employes['EMP003'] ?? null,
                    'date_debut' => $tache['statut'] !== 'a_faire' ? Carbon::now()->subDays(rand(1, 30)) : null,
                    'date_fin' => $tache['statut'] === 'termine' ? Carbon::now()->subDays(rand(1, 10)) : null,
                    'statut' => $tache['statut'],
                    'priorite' => $tache['priorite'],
                    'pourcentage_avancement' => $tache['avancement'],
                    'etat' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Seed l'équipe projet
     */
    private function seedEquipeProjets(array $projets, array $employes): void
    {
        $equipe = [
            'PRJ-2026-001' => ['EMP003', 'EMP004', 'EMP005', 'EMP009'],
            'PRJ-2026-002' => ['EMP003', 'EMP004', 'EMP005'],
            'PRJ-2026-003' => ['EMP003', 'EMP004', 'EMP005', 'EMP009']
        ];

        foreach ($equipe as $projetCode => $matricules) {
            foreach ($matricules as $matricule) {
                DB::table('equipe_projets')->insert([
                    'projet_id' => $projets[$projetCode],
                    'employe_id' => $employes[$matricule],
                    'role_sur_chantier' => 'Ouvrier',
                    'affecte_le' => Carbon::now()->subDays(rand(1, 30)),
                    'affecte_jusquau' => null,
                    'etat' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Seed les jalons
     */
    private function seedJalonProjets(array $projets): void
    {
        $jalons = [
            'PRJ-2026-001' => [
                ['nom' => 'Début des travaux', 'date_echeance' => '2026-01-15'],
                ['nom' => 'Fin terrassement', 'date_echeance' => '2026-03-15'],
                ['nom' => 'Fin gros œuvre', 'date_echeance' => '2026-09-15'],
                ['nom' => 'Réception travaux', 'date_echeance' => '2026-12-15']
            ],
            'PRJ-2026-002' => [
                ['nom' => 'Début terrassement', 'date_echeance' => '2026-02-01'],
                ['nom' => 'Fin terrassement', 'date_echeance' => '2026-06-30']
            ],
            'PRJ-2026-003' => [
                ['nom' => 'Début études', 'date_echeance' => '2026-03-01'],
                ['nom' => 'Fin études', 'date_echeance' => '2026-05-01']
            ]
        ];

        foreach ($jalons as $projetCode => $jalonList) {
            foreach ($jalonList as $jalon) {
                DB::table('jalon_projets')->insert([
                    'projet_id' => $projets[$projetCode],
                    'nom' => $jalon['nom'],
                    'date_echeance' => $jalon['date_echeance'],
                    'date_atteinte' => null,
                    'statut' => 'a_venir',
                    'etat' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Seed les équipements
     */
    private function seedEquipements(): void
    {
        // Catégories d'équipements
        $categories = [
            ['nom' => 'Engin de terrassement', 'etat' => 1],
            ['nom' => 'Véhicule', 'etat' => 1],
            ['nom' => 'Matériel de levage', 'etat' => 1],
            ['nom' => 'Engin de chantier', 'etat' => 1]
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $id = DB::table('categorie_equipements')->insertGetId([
                'nom' => $cat['nom'],
                'etat' => $cat['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $categoryIds[$cat['nom']] = $id;
        }

        $equipements = [
            [
                'code' => 'EQP-001',
                'nom' => 'Pelleteuse CAT 320',
                'categorie_nom' => 'Engin de terrassement',
                'marque' => 'Caterpillar',
                'modele' => '320',
                'numero_serie' => 'CAT320-001',
                'numero_immatriculation' => 'TG 1234 A',
                'date_achat' => '2020-01-15',
                'prix_achat' => 25000000,
                'statut' => 'disponible',
                'etat' => 1
            ],
            [
                'code' => 'EQP-002',
                'nom' => 'Camion 10T MAN',
                'categorie_nom' => 'Véhicule',
                'marque' => 'MAN',
                'modele' => 'TGS 10T',
                'numero_serie' => 'MAN002-001',
                'numero_immatriculation' => 'TG 5678 B',
                'date_achat' => '2021-06-01',
                'prix_achat' => 15000000,
                'statut' => 'en_service',
                'etat' => 1
            ],
            [
                'code' => 'EQP-003',
                'nom' => 'Grue à tour Potain',
                'categorie_nom' => 'Matériel de levage',
                'marque' => 'Potain',
                'modele' => 'MC 205',
                'numero_serie' => 'POT003-001',
                'numero_immatriculation' => null,
                'date_achat' => '2022-03-10',
                'prix_achat' => 35000000,
                'statut' => 'disponible',
                'etat' => 1
            ],
            [
                'code' => 'EQP-004',
                'nom' => 'Compacteur',
                'categorie_nom' => 'Engin de chantier',
                'marque' => 'Bomag',
                'modele' => 'BW 213',
                'numero_serie' => 'BOM004-001',
                'numero_immatriculation' => 'TG 9012 C',
                'date_achat' => '2021-09-20',
                'prix_achat' => 18000000,
                'statut' => 'disponible',
                'etat' => 1
            ]
        ];

        foreach ($equipements as $eqp) {
            DB::table('equipements')->insert([
                'categorie_id' => $categoryIds[$eqp['categorie_nom']],
                'code' => $eqp['code'],
                'nom' => $eqp['nom'],
                'marque' => $eqp['marque'],
                'modele' => $eqp['modele'],
                'numero_serie' => $eqp['numero_serie'],
                'numero_immatriculation' => $eqp['numero_immatriculation'],
                'date_achat' => $eqp['date_achat'],
                'prix_achat' => $eqp['prix_achat'],
                'statut' => $eqp['statut'],
                'etat' => $eqp['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les fournisseurs
     */
    private function seedFournisseurs(): void
    {
        $fournisseurs = [
            [
                'nom' => 'Cimenterie du Togo',
                'personne_contact' => 'M. A. Tchao',
                'telephone' => '+228 96666666',
                'email' => 'contact@cimenterie.tg',
                'adresse' => 'Lomé, Togo',
                'categorie' => 'materiaux',
                'note' => 4,
                'etat' => 1
            ],
            [
                'nom' => 'Steel Import SARL',
                'personne_contact' => 'Mme B. Koffi',
                'telephone' => '+228 97777777',
                'email' => 'info@steelimport.com',
                'adresse' => 'Lomé, Togo',
                'categorie' => 'materiaux',
                'note' => 5,
                'etat' => 1
            ],
            [
                'nom' => 'Énergie Plus',
                'personne_contact' => 'M. C. Mensah',
                'telephone' => '+228 98888888',
                'email' => 'contact@energieplus.tg',
                'adresse' => 'Kara, Togo',
                'categorie' => 'carburant',
                'note' => 3,
                'etat' => 1
            ],
            [
                'nom' => 'Matériaux Modernes',
                'personne_contact' => 'Mme D. Adjei',
                'telephone' => '+228 99999999',
                'email' => 'info@materiauxmodernes.com',
                'adresse' => 'Lomé, Togo',
                'categorie' => 'materiaux',
                'note' => 4,
                'etat' => 1
            ]
        ];

        foreach ($fournisseurs as $fournisseur) {
            DB::table('fournisseurs')->insert([
                'nom' => $fournisseur['nom'],
                'personne_contact' => $fournisseur['personne_contact'],
                'telephone' => $fournisseur['telephone'],
                'email' => $fournisseur['email'],
                'adresse' => $fournisseur['adresse'],
                'categorie' => $fournisseur['categorie'],
                'note' => $fournisseur['note'],
                'etat' => $fournisseur['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les catégories de matériaux
     */
    private function seedCategorieMateriaus(): array
    {
        $categories = [
            ['nom' => 'Ciment & liants', 'etat' => 1],
            ['nom' => 'Fers & aciers', 'etat' => 1],
            ['nom' => 'Bois', 'etat' => 1],
            ['nom' => 'Electricité', 'etat' => 1],
            ['nom' => 'Plomberie', 'etat' => 1],
            ['nom' => 'Peinture', 'etat' => 1],
            ['nom' => 'Carrelage', 'etat' => 1],
            ['nom' => 'Menuiserie', 'etat' => 1]
        ];

        foreach ($categories as $cat) {
            DB::table('categorie_materiaus')->insert([
                'nom' => $cat['nom'],
                'etat' => $cat['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('categorie_materiaus')->pluck('id', 'nom')->toArray();
    }

    /**
     * Seed les matériaux
     */
    private function seedMateriaus(array $categories): array
    {
        $materiaux = [
            [
                'code' => 'MAT-001',
                'nom' => 'Ciment CPJ 45',
                'categorie_nom' => 'Ciment & liants',
                'unite' => 'sac',
                'prix_unitaire' => 6500,
                'seuil_alerte_stock_min' => 100,
                'etat' => 1
            ],
            [
                'code' => 'MAT-002',
                'nom' => 'Fer 12mm',
                'categorie_nom' => 'Fers & aciers',
                'unite' => 'kg',
                'prix_unitaire' => 850,
                'seuil_alerte_stock_min' => 500,
                'etat' => 1
            ],
            [
                'code' => 'MAT-003',
                'nom' => 'Planche de coffrage',
                'categorie_nom' => 'Bois',
                'unite' => 'unite',
                'prix_unitaire' => 25000,
                'seuil_alerte_stock_min' => 50,
                'etat' => 1
            ],
            [
                'code' => 'MAT-004',
                'nom' => 'Câble électrique 3x2.5mm²',
                'categorie_nom' => 'Electricité',
                'unite' => 'ml',
                'prix_unitaire' => 1200,
                'seuil_alerte_stock_min' => 200,
                'etat' => 1
            ],
            [
                'code' => 'MAT-005',
                'nom' => 'Tube PVC 50mm',
                'categorie_nom' => 'Plomberie',
                'unite' => 'ml',
                'prix_unitaire' => 1800,
                'seuil_alerte_stock_min' => 100,
                'etat' => 1
            ],
            [
                'code' => 'MAT-006',
                'nom' => 'Peinture blanche 20L',
                'categorie_nom' => 'Peinture',
                'unite' => 'bidon',
                'prix_unitaire' => 45000,
                'seuil_alerte_stock_min' => 20,
                'etat' => 1
            ],
            [
                'code' => 'MAT-007',
                'nom' => 'Carreau 60x60',
                'categorie_nom' => 'Carrelage',
                'unite' => 'm2',
                'prix_unitaire' => 8500,
                'seuil_alerte_stock_min' => 100,
                'etat' => 1
            ],
            [
                'code' => 'MAT-008',
                'nom' => 'Sable',
                'categorie_nom' => 'Ciment & liants',
                'unite' => 'm3',
                'prix_unitaire' => 45000,
                'seuil_alerte_stock_min' => 10,
                'etat' => 1
            ]
        ];

        $materialIds = [];
        foreach ($materiaux as $mat) {
            $id = DB::table('materiaus')->insertGetId([
                'categorie_id' => $categories[$mat['categorie_nom']],
                'code' => $mat['code'],
                'nom' => $mat['nom'],
                'unite' => $mat['unite'],
                'prix_unitaire' => $mat['prix_unitaire'],
                'seuil_alerte_stock_min' => $mat['seuil_alerte_stock_min'],
                'description' => 'Description du matériau: ' . $mat['nom'],
                'etat' => $mat['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $materialIds[$mat['code']] = $id;
        }

        return $materialIds;
    }

    /**
     * Seed les entrepôts
     */
    private function seedEntrepots(array $projets): array
    {
        $entrepots = [
            [
                'nom' => 'Dépôt Central Lomé',
                'projet_code' => null,
                'emplacement' => 'Lomé, Togo',
                'etat' => 1
            ],
            [
                'nom' => 'Entrepôt Chantier R+5',
                'projet_code' => 'PRJ-2026-001',
                'emplacement' => 'Lomé, Togo',
                'etat' => 1
            ],
            [
                'nom' => 'Entrepôt Chantier Kara',
                'projet_code' => 'PRJ-2026-003',
                'emplacement' => 'Kara, Togo',
                'etat' => 1
            ]
        ];

        $entrepotIds = [];
        foreach ($entrepots as $entrepot) {
            $id = DB::table('entrepots')->insertGetId([
                'projet_id' => $entrepot['projet_code'] ? $projets[$entrepot['projet_code']] : null,
                'nom' => $entrepot['nom'],
                'emplacement' => $entrepot['emplacement'],
                'responsable_id' => null,
                'etat' => $entrepot['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $entrepotIds[$entrepot['nom']] = $id;
        }

        return $entrepotIds;
    }

    /**
     * Seed les niveaux de stock
     */
    private function seedNiveauStocks(array $entrepots, array $materiaux): void
    {
        $stocks = [
            'Dépôt Central Lomé' => [
                'MAT-001' => 250,
                'MAT-002' => 1500,
                'MAT-003' => 80,
                'MAT-004' => 300,
                'MAT-005' => 150,
                'MAT-006' => 30,
                'MAT-007' => 200,
                'MAT-008' => 25
            ],
            'Entrepôt Chantier R+5' => [
                'MAT-001' => 100,
                'MAT-002' => 600,
                'MAT-003' => 40,
                'MAT-004' => 120,
                'MAT-005' => 60,
                'MAT-006' => 15,
                'MAT-007' => 80,
                'MAT-008' => 10
            ]
        ];

        foreach ($stocks as $entrepotNom => $materiauQuantites) {
            foreach ($materiauQuantites as $materiauCode => $quantite) {
                DB::table('niveau_stocks')->insert([
                    'entrepot_id' => $entrepots[$entrepotNom],
                    'materiau_id' => $materiaux[$materiauCode],
                    'quantite' => $quantite,
                    'etat' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Seed les types de congés
     */
    private function seedTypeConges(): void
    {
        $types = [
            ['nom' => 'Congé payé', 'jours_autorises_par_an' => 30, 'est_paye' => true, 'etat' => 1],
            ['nom' => 'Congé maladie', 'jours_autorises_par_an' => 15, 'est_paye' => true, 'etat' => 1],
            ['nom' => 'Congé sans solde', 'jours_autorises_par_an' => null, 'est_paye' => false, 'etat' => 1],
            ['nom' => 'Congé maternité', 'jours_autorises_par_an' => 90, 'est_paye' => true, 'etat' => 1],
            ['nom' => 'Congé paternité', 'jours_autorises_par_an' => 10, 'est_paye' => true, 'etat' => 1]
        ];

        foreach ($types as $type) {
            DB::table('type_conges')->insert([
                'nom' => $type['nom'],
                'jours_autorises_par_an' => $type['jours_autorises_par_an'],
                'est_paye' => $type['est_paye'],
                'etat' => $type['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed le plan comptable
     */
    private function seedPlanComptables(): void
    {
        $comptes = [
            ['code' => '1', 'nom' => 'Capitaux propres', 'type' => 'capitaux', 'etat' => 1],
            ['code' => '2', 'nom' => 'Immobilisations', 'type' => 'actif', 'etat' => 1],
            ['code' => '3', 'nom' => 'Stocks', 'type' => 'actif', 'etat' => 1],
            ['code' => '4', 'nom' => 'Créances', 'type' => 'actif', 'etat' => 1],
            ['code' => '5', 'nom' => 'Disponibilités', 'type' => 'actif', 'etat' => 1],
            ['code' => '6', 'nom' => 'Charges', 'type' => 'charge', 'etat' => 1],
            ['code' => '7', 'nom' => 'Produits', 'type' => 'produit', 'etat' => 1],
            ['code' => '10', 'nom' => 'Capital social', 'type' => 'capitaux', 'parent_code' => '1', 'etat' => 1],
            ['code' => '20', 'nom' => 'Immobilisations incorporelles', 'type' => 'actif', 'parent_code' => '2', 'etat' => 1],
            ['code' => '21', 'nom' => 'Immobilisations corporelles', 'type' => 'actif', 'parent_code' => '2', 'etat' => 1],
            ['code' => '30', 'nom' => 'Stocks de matières', 'type' => 'actif', 'parent_code' => '3', 'etat' => 1],
            ['code' => '41', 'nom' => 'Clients', 'type' => 'actif', 'parent_code' => '4', 'etat' => 1],
            ['code' => '51', 'nom' => 'Banques', 'type' => 'actif', 'parent_code' => '5', 'etat' => 1],
            ['code' => '52', 'nom' => 'Caisses', 'type' => 'actif', 'parent_code' => '5', 'etat' => 1],
            ['code' => '60', 'nom' => 'Achats', 'type' => 'charge', 'parent_code' => '6', 'etat' => 1],
            ['code' => '70', 'nom' => 'Ventes', 'type' => 'produit', 'parent_code' => '7', 'etat' => 1],
        ];

        $parentIds = [];
        foreach ($comptes as $compte) {
            $parentId = null;
            if (isset($compte['parent_code'])) {
                $parentId = $parentIds[$compte['parent_code']] ?? null;
            }

            $id = DB::table('plan_comptables')->insertGetId([
                'code' => $compte['code'],
                'nom' => $compte['nom'],
                'type' => $compte['type'],
                'parent_id' => $parentId,
                'etat' => $compte['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $parentIds[$compte['code']] = $id;
        }
    }

    /**
     * Seed les périodes de paie
     */
    private function seedPeriodePaies(): void
    {
        $periodes = [
            ['nom' => 'Paie Janvier 2026', 'date_debut' => '2026-01-01', 'date_fin' => '2026-01-31', 'statut' => 'cloture', 'etat' => 1],
            ['nom' => 'Paie Février 2026', 'date_debut' => '2026-02-01', 'date_fin' => '2026-02-28', 'statut' => 'cloture', 'etat' => 1],
            ['nom' => 'Paie Mars 2026', 'date_debut' => '2026-03-01', 'date_fin' => '2026-03-31', 'statut' => 'ouvert', 'etat' => 1],
        ];

        foreach ($periodes as $periode) {
            DB::table('periode_paies')->insert([
                'nom' => $periode['nom'],
                'date_debut' => $periode['date_debut'],
                'date_fin' => $periode['date_fin'],
                'statut' => $periode['statut'],
                'etat' => $periode['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les sous-traitants
     */
    private function seedSoustraitants(): void
    {
        $sousTraitants = [
            [
                'nom_entreprise' => 'Électricité Moderne SARL',
                'personne_contact' => 'M. K. Agbéko',
                'telephone' => '+228 96667777',
                'email' => 'contact@electricitemoderne.com',
                'adresse' => 'Lomé, Togo',
                'specialite' => 'electricite',
                'note' => 4,
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'nom_entreprise' => 'Plomberie Pro',
                'personne_contact' => 'Mme A. Dossou',
                'telephone' => '+228 97778888',
                'email' => 'info@plomberiepro.tg',
                'adresse' => 'Kara, Togo',
                'specialite' => 'plomberie',
                'note' => 5,
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'nom_entreprise' => 'Menuiserie Excellence',
                'personne_contact' => 'M. T. Mensah',
                'telephone' => '+228 98889999',
                'email' => 'contact@menusierieexcellence.com',
                'adresse' => 'Lomé, Togo',
                'specialite' => 'menuiserie',
                'note' => 3,
                'statut' => 'actif',
                'etat' => 1
            ],
            [
                'nom_entreprise' => 'Étanchéité Total',
                'personne_contact' => 'M. C. Agbovor',
                'telephone' => '+228 99990000',
                'email' => 'info@etancheitetotal.tg',
                'adresse' => 'Sokodé, Togo',
                'specialite' => 'etancheite',
                'note' => 4,
                'statut' => 'actif',
                'etat' => 1
            ]
        ];

        foreach ($sousTraitants as $st) {
            DB::table('soustraitants')->insert([
                'nom_entreprise' => $st['nom_entreprise'],
                'personne_contact' => $st['personne_contact'],
                'telephone' => $st['telephone'],
                'email' => $st['email'],
                'adresse' => $st['adresse'],
                'specialite' => $st['specialite'],
                'note' => $st['note'],
                'statut' => $st['statut'],
                'etat' => $st['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les comptes bancaires
     */
    private function seedCompteBancaires(): void
    {
        $comptes = [
            [
                'nom' => 'Compte Principal BTP',
                'nom_banque' => 'Ecobank Togo',
                'numero_compte' => 'ECOBANK001',
                'iban' => 'TG12345678901234567890',
                'solde_actuel' => 150000000,
                'etat' => 1
            ],
            [
                'nom' => 'Compte Chantier R+5',
                'nom_banque' => 'Ecobank Togo',
                'numero_compte' => 'ECOBANK002',
                'iban' => 'TG09876543210987654321',
                'solde_actuel' => 50000000,
                'etat' => 1
            ]
        ];

        foreach ($comptes as $compte) {
            DB::table('compte_bancaires')->insert([
                'nom' => $compte['nom'],
                'nom_banque' => $compte['nom_banque'],
                'numero_compte' => $compte['numero_compte'],
                'iban' => $compte['iban'],
                'solde_actuel' => $compte['solde_actuel'],
                'etat' => $compte['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed les caisses
     */
    private function seedCaisses(): void
    {
        $caisses = [
            [
                'nom' => 'Caisse Siege',
                'projet_id' => null,
                'responsable_id' => null,
                'solde_actuel' => 25000000,
                'etat' => 1
            ],
            [
                'nom' => 'Caisse Chantier R+5',
                'projet_id' => null,
                'responsable_id' => null,
                'solde_actuel' => 5000000,
                'etat' => 1
            ]
        ];

        foreach ($caisses as $caisse) {
            DB::table('caisses')->insert([
                'nom' => $caisse['nom'],
                'projet_id' => $caisse['projet_id'],
                'responsable_id' => $caisse['responsable_id'],
                'solde_actuel' => $caisse['solde_actuel'],
                'etat' => $caisse['etat'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
