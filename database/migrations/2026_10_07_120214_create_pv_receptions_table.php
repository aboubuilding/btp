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
        Schema::create('pv_receptions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->enum('type', ['partielle','provisoire','definitive']);
    $table->date('date_reception');
    $table->boolean('avec_reserves')->default(false);
    $table->string('chemin_fichier')->nullable();
    $table->enum('statut', ['brouillon','signe'])->default('brouillon');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_receptions');
    }
};
