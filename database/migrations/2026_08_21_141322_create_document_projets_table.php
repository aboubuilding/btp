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
        Schema::create('document_projets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('projet_id')->nullable();
            $table->string('nom');
            $table->string('type')->nullable(); // plan, permis, photo, rapport, contrat...
            $table->string('chemin_fichier');
            $table->unsignedBigInteger('telecharge_par')->nullable();
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_projets');
    }
};
