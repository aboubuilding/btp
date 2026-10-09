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
        Schema::create('niveau_stocks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('entrepot_id');
    $table->unsignedBigInteger('materiau_id');
    $table->decimal('quantite', 14, 3)->default(0);
    $table->decimal('cmup', 15, 2)->default(0);       // coût moyen unitaire pondéré
    $table->unique(['entrepot_id', 'materiau_id']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_stocks');
    }
};
