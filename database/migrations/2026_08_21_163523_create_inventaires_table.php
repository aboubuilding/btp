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
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrepot_id')->nullable();
            $table->date('date_inventaire');
            $table->unsignedBigInteger('effectue_par')->nullable();
            $table->enum('statut', ['en_cours', 'valide'])->default('en_cours');

            $table->integer('etat')->default(1);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaires');
    }
};
