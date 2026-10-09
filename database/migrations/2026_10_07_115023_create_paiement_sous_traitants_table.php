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
        Schema::create('paiement_sous_traitants', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('facture_sous_traitant_id')->index();
    $table->decimal('montant', 15, 2);
    $table->date('date_paiement');
    $table->enum('mode', ['especes','cheque','virement','mobile_money']);
    $table->string('reference')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_sous_traitants');
    }
};
