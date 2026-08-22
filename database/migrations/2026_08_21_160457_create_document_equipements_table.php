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

            $table->unsignedBigInteger('equipement_id')->nullable();
            $table->string('type'); // carte_grise, assurance, visite_technique
            $table->string('chemin_fichier');
            $table->date('date_emission')->nullable();
            $table->date('date_expiration')->nullable();

            $table->integer('etat')->default(1);
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
