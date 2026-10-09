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
        Schema::create('factures', function (Blueprint $table) {
    $table->id();
    $table->string('numero_facture')->unique();
    $table->enum('type', ['client','fournisseur']);
    $table->string('type_facturable');
    $table->unsignedBigInteger('id_facturable');
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->unsignedBigInteger('situation_id')->nullable();
    $table->unsignedBigInteger('bon_commande_id')->nullable();
    $table->date('date_facture');
    $table->date('date_echeance')->nullable();
    $table->decimal('montant_ht', 15, 2);
    $table->decimal('tva', 15, 2)->default(0);
    $table->decimal('montant_ttc', 15, 2);
    $table->decimal('retenue_garantie', 15, 2)->default(0);
    $table->decimal('remboursement_avance', 15, 2)->default(0);
    $table->decimal('net_a_payer', 15, 2)->default(0);
    $table->decimal('montant_paye', 15, 2)->default(0);
    $table->enum('statut', ['emise','partiellement_payee','payee','annulee'])->default('emise');
    $table->index(['type_facturable', 'id_facturable']);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
