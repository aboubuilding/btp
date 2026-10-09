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
    $table->unsignedBigInteger('livraison_id')->index();
    $table->unsignedBigInteger('materiau_id');
    $table->decimal('quantite_commandee', 14, 3)->default(0);
    $table->decimal('quantite_recue', 14, 3)->default(0);
    $table->enum('etat_article', ['bon_etat','endommage','non_conforme'])->default('bon_etat');
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
