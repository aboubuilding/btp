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
        Schema::create('compte_bancaires', function (Blueprint $table) {
    $table->id();
    $table->string('libelle');
    $table->string('numero');
    $table->string('banque')->nullable();
    $table->decimal('solde_initial', 15, 2)->default(0);
    $table->decimal('solde_actuel', 15, 2)->default(0);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compte_bancaires');
    }
};
