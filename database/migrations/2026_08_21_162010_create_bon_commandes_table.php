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
        Schema::create('bon_commandes', function (Blueprint $table) {
            $table->id();

            $table->string('numero_commande')->unique();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->foreignId('demande_achat_id')->nullable()->constrained('demandes_achat')->nullOnDelete();
            $table->date('date_commande');
            $table->date('date_livraison_prevue')->nullable();
            $table->enum('statut', ['brouillon', 'envoyee', 'confirmee', 'livree_partiellement', 'livree', 'annulee'])->default('brouillon');
            $table->decimal('montant_total', 15, 2)->default(0);
            $table->foreignId('cree_par')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_commandes');
    }
};
