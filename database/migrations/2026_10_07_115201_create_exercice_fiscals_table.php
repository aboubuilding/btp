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
        Schema::create('exercice_fiscals', function (Blueprint $table) {
    $table->id();
    $table->string('libelle');
    $table->date('date_debut');
    $table->date('date_fin');
    $table->enum('statut', ['ouvert','cloture'])->default('ouvert');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercice_fiscals');
    }
};
