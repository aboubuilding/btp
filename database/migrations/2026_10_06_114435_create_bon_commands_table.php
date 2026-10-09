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
        Schema::create('bon_commands', function (Blueprint $table) {
    $table->id();
    $table->string('numero')->unique();            // BC-2026-0001
    $table->unsignedBigInteger('fournisseur_id')->index();
    $table->unsignedBigInteger('demande_achat_id')->nullable();
    $table->date('date_commande');
    $table->date('date_livraison_prevue')->nullable();
    $table->decimal('montant_total', 15, 2)->default(0);
    $table->enum('statut', ['brouillon','envoye','confirme','livre_partiellement','livre','annule'])->default('brouillon')->index();
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
        Schema::dropIfExists('bon_commands');
    }
};
