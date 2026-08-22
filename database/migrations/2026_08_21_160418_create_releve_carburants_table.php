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
        Schema::create('releve_carburants', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('equipement_id')->nullable();
            $table->unsignedBigInteger('projet_id')->nullable();
            $table->date('date');
            $table->decimal('quantite_litres', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('cout_total', 12, 2);
            $table->decimal('compteur_heures', 12, 2)->nullable();
            $table->foreignId('rempli_par')->nullable()->constrained('employes')->nullOnDelete();

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releve_carburants');
    }
};
