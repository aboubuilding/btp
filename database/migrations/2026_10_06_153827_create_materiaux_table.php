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
      Schema::create('materiaux', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('categorie_id')->nullable();
    $table->string('code')->unique();
    $table->string('nom');
    $table->string('unite');
    $table->decimal('prix_unitaire', 12, 2)->default(0);
    $table->decimal('seuil_alerte_stock_min', 12, 2)->default(0);
    $table->string('source_reprise', 50)->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiaux');
    }
};
