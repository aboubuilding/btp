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
        Schema::create('incident_securites', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->dateTime('date_incident');
    $table->enum('type', ['accident_travail','presque_accident','incident_materiel','environnement']);
    $table->enum('gravite', ['mineure','moyenne','grave','mortelle'])->index();
    $table->text('description');
    $table->unsignedSmallInteger('nombre_victimes')->default(0);
    $table->unsignedInteger('jours_arret')->default(0);
    $table->text('causes')->nullable();
    $table->text('actions_correctives')->nullable();
    $table->unsignedBigInteger('declare_par')->nullable();
    $table->enum('statut', ['declare','en_analyse','clos'])->default('declare');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_securites');
    }
};
