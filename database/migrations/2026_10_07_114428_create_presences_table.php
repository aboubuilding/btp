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
        Schema::create('presences', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('employee_id');
    $table->unsignedBigInteger('projet_id')->nullable();
    $table->date('date');
    $table->time('heure_arrivee')->nullable();
    $table->time('heure_depart')->nullable();
    $table->decimal('heures_travaillees', 5, 2)->nullable();
    $table->decimal('heures_supplementaires', 5, 2)->default(0);
    $table->enum('statut', ['present','absent','retard','conge','maladie','ferie'])->default('present');
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->unsignedBigInteger('enregistre_par')->nullable();
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->text('notes')->nullable();
    $table->unique(['employee_id', 'date']);
    $table->index(['projet_id', 'date']);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
