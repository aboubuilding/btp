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
        Schema::create('facture_soustraitants', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('contrat_sous_traitant_id')->nullable();
            $table->string('numero_facture')->unique();
            $table->date('date_facture');
            $table->decimal('montant', 15, 2);
            $table->enum('statut', ['emise', 'payee', 'partiellement_payee', 'contestee'])->default('emise');
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_soustraitants');
    }
};
