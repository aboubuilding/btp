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
        Schema::create('article_livraisons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('livraison_id')->nullable();
            $table->unsignedBigInteger('materiau_id')->nullable();
            $table->decimal('quantite_commandee', 14, 3);
            $table->decimal('quantite_recue', 14, 3);
            $table->enum('condition', ['bon_etat', 'endommage', 'non_conforme'])->default('bon_etat');
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_livraisons');
    }
};
