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
        Schema::create('facture_sous_traitants', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('contrat_sous_traitant_id')->index();
    $table->string('numero');
    $table->date('date_facture');
    $table->decimal('montant_ht', 15, 2);
    $table->decimal('tva', 15, 2)->default(0);
    $table->decimal('montant_ttc', 15, 2);
    $table->decimal('retenue_garantie', 15, 2)->default(0);
    $table->enum('statut', ['recue','validee','payee','annulee'])->default('recue');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_sous_traitants');
    }
};
