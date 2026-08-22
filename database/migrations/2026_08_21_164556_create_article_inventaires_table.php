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
        Schema::create('article_inventaires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inventaire_id')->nullable();
            $table->unsignedBigInteger('materiau_id')->nullable();
            $table->decimal('quantite_theorique', 14, 3);
            $table->decimal('quantite_comptee', 14, 3);
            $table->decimal('ecart', 14, 3)->default(0);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_inventaires');
    }
};
