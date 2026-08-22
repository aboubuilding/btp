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
        Schema::create('phase_projets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('projet_id')->nullable();
            $table->string('nom'); // Terrassement, Fondations, Gros oeuvre, Second oeuvre, Finitions...
            $table->unsignedInteger('ordre')->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->unsignedTinyInteger('pourcentage_avancement')->default(0);
            $table->enum('statut', ['a_venir', 'en_cours', 'termine'])->default('a_venir');

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phase_projets');
    }
};
