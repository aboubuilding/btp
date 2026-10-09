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
        Schema::create('jalon_projets', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->string('libelle');                       // OS, réception partielle, provisoire
    $table->date('date_echeance')->nullable();
    $table->date('date_atteinte')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalon_projets');
    }
};
