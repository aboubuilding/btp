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
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->string('code')->unique();
            $table->string('nom');
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('numero_serie')->nullable();
            $table->string('numero_immatriculation')->nullable(); // immatriculation
            $table->date('date_achat')->nullable();
            $table->decimal('prix_achat', 15, 2)->nullable();
            $table->decimal('valeur_actuelle', 15, 2)->nullable();
            $table->decimal('compteur_heures_actuel', 12, 2)->default(0);
            $table->enum('statut', ['disponible', 'en_service', 'en_panne', 'en_maintenance', 'hors_service'])->default('disponible');
            $table->unsignedBigInteger('projet_actuel_id')->nullable();
            $table->integer('etat')->default(1);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
