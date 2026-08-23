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
        Schema::create('dependance_taches', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tache_id')->nullable();
            $table->unsignedBigInteger('depend_de_tache_id')->nullable();
            $table->enum('type', ['fin_debut', 'debut_debut', 'fin_fin', 'debut_fin'])->default('fin_debut');

            $table->unique(['tache_id', 'depend_de_tache_id']);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependance_taches');
    }
};
