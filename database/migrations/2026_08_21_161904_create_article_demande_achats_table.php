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
            $table->unsignedBigInteger('demande_achat_id')->nullable();
            $table->unsignedBigInteger('materiau_id')->nullable();
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
