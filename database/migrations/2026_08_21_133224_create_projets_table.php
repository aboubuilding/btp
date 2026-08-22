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
        Schema::create('projets', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('nom');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable(); // batiment, route, ouvrage_art, terrassement, reseau...
            $table->unsignedBigInteger('conducteur_travaux_id')->nullable();
            $table->unsignedBigInteger('chef_chantier_id')->nullable();
            $table->date('date_debut_prevue')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_debut_reelle')->nullable();
            $table->date('date_fin_reelle')->nullable();
            $table->decimal('budget_prevue', 15, 2)->default(0);
            $table->decimal('budget_reel', 15, 2)->default(0);
            $table->decimal('montant_contrat', 15, 2)->default(0);
            $table->unsignedTinyInteger('pourcentage_avancement')->default(0);
            $table->enum('statut', ['planifie', 'en_cours', 'suspendu', 'termine', 'annule'])->default('planifie');

            $table->integer('etat')->default(1);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
