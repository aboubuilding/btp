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
       // avancement_projets = journal de chantier
Schema::create('avancement_projets', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->date('date_rapport');
    $table->string('meteo')->nullable();
    $table->unsignedSmallInteger('ouvriers_presents')->default(0);
    $table->text('materiel_present')->nullable();
    $table->text('livraisons_recues')->nullable();
    $table->text('visiteurs')->nullable();
    $table->text('travaux_realises')->nullable();
    $table->text('incidents')->nullable();
    $table->text('difficultes')->nullable();
    $table->unsignedTinyInteger('pourcentage_avancement')->default(0);
    $table->unsignedBigInteger('saisi_par')->nullable();
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->unique(['projet_id', 'date_rapport']);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avancement_projets');
    }
};
