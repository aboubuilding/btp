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
        // devis + lot_devis + ligne_devis
Schema::create('devis', function (Blueprint $table) {
    $table->id();
    $table->string('numero')->unique();                // DEV-2026-0001
    $table->unsignedBigInteger('client_id')->nullable()->index();
    $table->string('objet');
    $table->date('date_devis');
    $table->unsignedSmallInteger('validite_jours')->default(30);
    $table->decimal('coefficient_vente', 6, 3)->default(1);
    $table->decimal('total_debourse_sec', 15, 2)->default(0);
    $table->decimal('montant_ht', 15, 2)->default(0);
    $table->decimal('taux_tva', 5, 2)->default(18);
    $table->decimal('montant_ttc', 15, 2)->default(0);
    $table->enum('statut', ['brouillon','envoye','accepte','refuse','expire'])->default('brouillon')->index();
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
