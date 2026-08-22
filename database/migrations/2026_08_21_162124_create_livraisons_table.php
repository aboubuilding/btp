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

            $table->unsignedBigInteger('bon_commande_id')->nullable();
            $table->unsignedBigInteger('entrepot_id')->nullable();
            $table->date('date_livraison');
            $table->unsignedBigInteger('recue_par')->nullable();
            $table->enum('statut', ['partielle', 'complete', 'refusee'])->default('complete');
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
        Schema::dropIfExists('livraisons');
    }
};
