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
        Schema::create('affectation_equipements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipement_id')->constrained('equipements')->cascadeOnDelete();
            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->foreignId('affecte_par')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('compteur_heures_debut', 12, 2)->nullable();
            $table->decimal('compteur_heures_fin', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectation_equipements');
    }
};
