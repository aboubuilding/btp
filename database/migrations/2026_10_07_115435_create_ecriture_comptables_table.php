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
        Schema::create('ecriture_comptables', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('exercice_fiscal_id')->index();
    $table->string('numero');
    $table->date('date_ecriture');
    $table->string('libelle');
    $table->enum('statut', ['brouillon','validee'])->default('brouillon');
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecriture_comptables');
    }
};
