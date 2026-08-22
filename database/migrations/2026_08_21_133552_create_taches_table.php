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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->foreignId('phase_id')->nullable()->constrained('phases_projet')->nullOnDelete();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->foreignId('assigne_a')->nullable()->constrained('employes')->nullOnDelete();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->unsignedInteger('duree_jours')->nullable();
            $table->unsignedTinyInteger('pourcentage_avancement')->default(0);
            $table->enum('priorite', ['basse', 'normale', 'haute', 'critique'])->default('normale');
            $table->enum('statut', ['a_faire', 'en_cours', 'en_retard', 'termine'])->default('a_faire');

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
