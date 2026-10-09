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
        Schema::create('equipe_projets', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->unsignedBigInteger('employee_id')->index();
    $table->string('role_chantier')->nullable();     // chef_equipe, ouvrier, pointeur
    $table->date('date_debut')->nullable();
    $table->date('date_fin')->nullable();
    $table->unique(['projet_id', 'employee_id', 'date_debut']);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe_projets');
    }
};
