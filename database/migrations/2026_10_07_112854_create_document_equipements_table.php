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
        Schema::create('document_equipements', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipement_id')->index();
    $table->enum('type', ['assurance','visite_technique','carte_grise','autre']);
    $table->string('numero')->nullable();
    $table->date('date_emission')->nullable();
    $table->date('date_expiration')->nullable();
    $table->string('chemin_fichier')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_equipements');
    }
};
