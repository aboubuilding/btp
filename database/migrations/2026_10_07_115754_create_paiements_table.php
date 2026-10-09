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
        Schema::create('paiements', function (Blueprint $table) {
    $table->id();
    $table->string('numero_paiement')->unique();
    $table->enum('sens', ['encaissement','decaissement']);
    $table->string('type_payable');
    $table->unsignedBigInteger('id_payable');
    $table->decimal('montant', 15, 2);
    $table->date('date_paiement');
    $table->enum('mode', ['especes','cheque','virement','mobile_money']);
    $table->unsignedBigInteger('compte_bancaire_id')->nullable();
    $table->unsignedBigInteger('caisse_id')->nullable();
    $table->string('reference')->nullable();
    $table->index(['type_payable', 'id_payable']);
    $table->integer('etat')->default(1);
    $table->timestamps();
}); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
