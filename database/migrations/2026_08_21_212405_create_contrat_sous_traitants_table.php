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
        Schema::create('contrat_sous_traitants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sous_traitant_id')->constrained('sous_traitants')->cascadeOnDelete();
            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->string('numero_contrat')->unique();
            $table->text('description')->nullable();
            $table->decimal('montant', 15, 2);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'resilie'])->default('en_cours');
            $table->string('chemin_fichier')->nullable();

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_sous_traitants');
    }
};
