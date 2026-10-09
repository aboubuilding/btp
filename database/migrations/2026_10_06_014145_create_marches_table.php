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
        Schema::create('marches', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();
    $table->unsignedBigInteger('client_id')->nullable()->index();
    $table->unsignedBigInteger('devis_id')->nullable();
    $table->string('objet');
    $table->date('date_signature')->nullable();
    $table->date('date_ordre_service')->nullable();
    $table->decimal('montant_initial', 15, 2)->default(0);
    $table->unsignedSmallInteger('delai_contractuel_jours')->nullable();
    $table->decimal('taux_avance', 5, 2)->default(0);
    $table->string('mode_remboursement_avance')->nullable();
    $table->decimal('taux_retenue_garantie', 5, 2)->default(0);
    $table->enum('statut', ['brouillon','signe','en_cours','receptionne','clos'])->default('brouillon')->index();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marches');
    }
};
