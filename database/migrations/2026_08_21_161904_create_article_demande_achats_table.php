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
        Schema::create('article_demande_achats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_achat_id')->constrained('demandes_achat')->cascadeOnDelete();
            $table->foreignId('materiau_id')->constrained('materiaux')->cascadeOnDelete();
            $table->decimal('quantite_demandee', 14, 3);
            $table->text('notes')->nullable();
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_demande_achats');
    }
};
