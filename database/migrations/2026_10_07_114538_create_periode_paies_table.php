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
        Schema::create('periode_paies', function (Blueprint $table) {
    $table->id();
    $table->string('libelle');                    // Semaine 12 - 2026
    $table->date('date_debut');
    $table->date('date_fin');
    $table->enum('type', ['hebdomadaire','mensuelle']);
    $table->enum('statut', ['ouverte','cloturee','payee'])->default('ouverte')->index();
    $table->unsignedBigInteger('cloture_par')->nullable();
    $table->timestamp('cloture_le')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_paies');
    }
};
