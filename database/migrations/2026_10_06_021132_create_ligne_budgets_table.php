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
        Schema::create('ligne_budgets', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->string('libelle');                       // Matériaux, main d'œuvre, matériel...
    $table->decimal('montant_prevu', 15, 2)->default(0);
    $table->decimal('montant_reel', 15, 2)->default(0);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_budgets');
    }
};
