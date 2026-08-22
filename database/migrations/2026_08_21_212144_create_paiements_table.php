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
            $table->string('type_payable'); // Invoice, SubcontractorInvoice...
            $table->unsignedBigInteger('id_payable');
            $table->decimal('montant', 15, 2);
            $table->date('date_paiement');
            $table->enum('mode', ['especes', 'cheque', 'virement', 'mobile_money']);
            $table->foreignId('compte_bancaire_id')->nullable()->constrained('comptes_bancaires')->nullOnDelete();
            $table->foreignId('caisse_id')->nullable()->constrained('caisses')->nullOnDelete();
            $table->string('reference')->nullable();
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
