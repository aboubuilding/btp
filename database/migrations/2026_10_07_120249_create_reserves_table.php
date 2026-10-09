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
        Schema::create('reserves', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('pv_reception_id')->index();
    $table->string('localisation')->nullable();
    $table->text('description');
    $table->unsignedBigInteger('responsable_id')->nullable();
    $table->string('type_responsable')->nullable(); // Employe, Soustraitant
    $table->date('date_limite')->nullable();
    $table->date('date_levee')->nullable();
    $table->enum('statut', ['ouverte','levee'])->default('ouverte');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
