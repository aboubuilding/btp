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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->string('numero_contrat')->unique();
            $table->enum('type', ['cdi', 'cdd', 'journalier', 'stage', 'prestataire']);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('salaire', 12, 2);
            $table->string('chemin_fichier')->nullable();
            $table->enum('statut', ['actif', 'expire', 'resilie'])->default('actif');

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
