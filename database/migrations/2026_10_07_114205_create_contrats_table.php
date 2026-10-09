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
    $table->unsignedBigInteger('employee_id')->index();
    $table->string('numero')->unique();
    $table->enum('type', ['cdi','cdd','journalier','stage','prestataire']);
    $table->date('date_debut');
    $table->date('date_fin')->nullable();
    $table->decimal('salaire_base', 15, 2);
    $table->enum('statut', ['en_cours','termine','resilie'])->default('en_cours');
    $table->string('chemin_fichier')->nullable();
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
