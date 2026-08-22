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
        Schema::create('panne_equipements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipement_id')->constrained('equipements')->cascadeOnDelete();
            $table->foreignId('projet_id')->nullable()->constrained('projets')->nullOnDelete();
            $table->foreignId('signale_par')->nullable()->constrained('employes')->nullOnDelete();
            $table->date('date_panne');
            $table->text('description');
            $table->date('date_reparation')->nullable();
            $table->decimal('cout_reparation', 12, 2)->nullable();
            $table->decimal('heures_indisponibilite', 8, 2)->nullable();
            $table->enum('statut', ['signale', 'en_reparation', 'resolu'])->default('signale');
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panne_equipements');
    }
};
