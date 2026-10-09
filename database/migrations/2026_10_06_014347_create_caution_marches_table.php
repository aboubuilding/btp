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
        Schema::create('caution_marches', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('marche_id')->index();
    $table->enum('type', ['soumission','avance','bonne_execution']);
    $table->decimal('montant', 15, 2);
    $table->string('banque')->nullable();
    $table->date('date_emission')->nullable();
    $table->date('date_echeance')->nullable();
    $table->enum('statut', ['active','liberee','appelee'])->default('active');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caution_marches');
    }
};
