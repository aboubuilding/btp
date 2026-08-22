<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // compte de connexion, optionnel pour ouvrier de chantier
            $table->string('matricule')->unique();
            $table->string('prenom');
            $table->string('nom');
            $table->enum('genre', ['homme', 'femme']);
            $table->date('date_naissance')->nullable();
            $table->string('cin')->nullable(); // Carte d'Identité Nationale
            $table->string('numero_cnss')->nullable();
            $table->date('date_embauche');
            $table->date('date_fin')->nullable();
            $table->foreignId('departement_id')->nullable()->constrained('departements')->nullOnDelete();
            $table->foreignId('poste_id')->nullable()->constrained('postes')->nullOnDelete();
            $table->enum('type_contrat', ['cdi', 'cdd', 'journalier', 'stage', 'prestataire'])->default('cdi');
            $table->decimal('salaire_base', 12, 2)->default(0);
            $table->string('mode_paiement')->default('mensuel'); // mensuel, journalier, horaire
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'inactif'])->default('actif');
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
