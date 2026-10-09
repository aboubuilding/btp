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
        Schema::create('affectation_equipements', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipement_id')->index();
    $table->unsignedBigInteger('projet_id')->index();
    $table->date('date_debut');
    $table->date('date_fin')->nullable();
    $table->decimal('compteur_debut', 12, 2)->default(0);
    $table->decimal('compteur_fin', 12, 2)->nullable();
    $table->decimal('cout_impute', 15, 2)->default(0);
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->enum('statut', ['ouverte','fermee'])->default('ouverte');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectation_equipements');
    }
};
