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
        Schema::create('livraisons', function (Blueprint $table) {
    $table->id();
    $table->string('numero')->unique();
    $table->unsignedBigInteger('bon_commande_id')->index();
    $table->unsignedBigInteger('entrepot_id')->index();
    $table->date('date_livraison');
    $table->unsignedBigInteger('receptionnaire_id')->nullable();
    $table->enum('statut', ['partielle','complete','refusee'])->default('partielle');
    $table->text('observation')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};
