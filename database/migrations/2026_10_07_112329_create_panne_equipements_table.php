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
    $table->unsignedBigInteger('equipement_id')->index();
    $table->date('date_panne');
    $table->text('description');
    $table->decimal('cout_reparation', 15, 2)->default(0);
    $table->unsignedInteger('heures_immobilisation')->default(0);
    $table->date('date_cloture')->nullable();
    $table->unsignedBigInteger('declare_par')->nullable();
    $table->enum('statut', ['declaree','en_reparation','cloturee'])->default('declaree');
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
