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
        Schema::create('fournisseurs', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('contact')->nullable();
    $table->string('telephone')->nullable();
    $table->string('email')->nullable();
    $table->string('adresse')->nullable();
    $table->enum('categorie', ['materiaux','carburant','pieces_detachees','location_engins','services','divers'])->nullable();
    $table->decimal('note_evaluation', 3, 1)->nullable();
    $table->string('source_reprise', 50)->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
