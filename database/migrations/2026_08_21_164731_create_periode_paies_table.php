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
        Schema::create('periode_paies', function (Blueprint $table) {
            $table->id();

            $table->string('nom'); // ex: Paie Janvier 2026
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['ouvert', 'cloture', 'paye'])->default('ouvert');

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_paies');
    }
};
