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
        Schema::create('non_conformites', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->date('date_constat');
    $table->string('ouvrage')->nullable();
    $table->text('description');
    $table->text('action_corrective')->nullable();
    $table->unsignedBigInteger('responsable_id')->nullable();
    $table->date('date_levee')->nullable();
    $table->enum('statut', ['ouverte','en_traitement','levee'])->default('ouverte');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_conformites');
    }
};
