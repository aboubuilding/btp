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
        Schema::create('transfert_stocks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('entrepot_source_id');
    $table->unsignedBigInteger('entrepot_destination_id');
    $table->unsignedBigInteger('materiau_id');
    $table->decimal('quantite', 14, 3);
    $table->enum('statut', ['en_attente','approuve','rejete'])->default('en_attente');
    $table->unsignedBigInteger('demande_par')->nullable();
    $table->unsignedBigInteger('approuve_par')->nullable();
    $table->timestamp('approuve_le')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_stocks');
    }
};
