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
        Schema::create('article_bon_commands', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('bon_commande_id')->index();
    $table->unsignedBigInteger('materiau_id')->nullable();
    $table->string('designation');
    $table->decimal('quantite', 14, 3);
    $table->string('unite');
    $table->decimal('prix_unitaire', 15, 2);
    $table->decimal('montant', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_bon_commands');
    }
};
