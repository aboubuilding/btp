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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->string('nom'); // Administrateur, Direction, Conducteur de travaux, Chef de chantier, Responsable achats, Comptable, RH
            $table->string('slug')->unique(); // admin, direction, conducteur_travaux, chef_chantier, responsable_achat, comptable, rh
            $table->string('description')->nullable();

            $table->integer('etat')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
