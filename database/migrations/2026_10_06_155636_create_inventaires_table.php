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
        Schema::create('inventaires', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('entrepot_id');
    $table->date('date_inventaire');
    $table->enum('statut', ['brouillon','valide'])->default('brouillon');
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};
