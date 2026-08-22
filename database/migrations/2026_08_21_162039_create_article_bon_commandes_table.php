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
        Schema::create('article_bon_commandes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bon_commande_id')->constrained('bons_commande')->cascadeOnDelete();
            $table->foreignId('materiau_id')->constrained('materiaux')->cascadeOnDelete();
            $table->decimal('quantite', 14, 3);
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('total', 14, 2);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_bon_commandes');
    }
};
