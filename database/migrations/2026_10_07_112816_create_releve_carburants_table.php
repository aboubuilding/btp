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
        Schema::create('releve_carburants', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipement_id')->index();
    $table->date('date_releve');
    $table->decimal('quantite', 10, 2);
    $table->decimal('prix_unitaire', 12, 2);
    $table->decimal('cout_total', 15, 2);
    $table->decimal('compteur', 12, 2)->nullable();
    $table->decimal('consommation_horaire', 8, 2)->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releve_carburants');
    }
};
