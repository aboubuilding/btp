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
       Schema::create('situations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->unsignedBigInteger('attachement_id')->nullable();
    $table->unsignedSmallInteger('numero');
    $table->date('periode_debut');
    $table->date('periode_fin');
    $table->decimal('montant_cumule_ht', 15, 2)->default(0);
    $table->decimal('montant_precedent_ht', 15, 2)->default(0);
    $table->decimal('montant_periode_ht', 15, 2)->default(0);
    $table->decimal('remboursement_avance', 15, 2)->default(0);
    $table->decimal('retenue_garantie', 15, 2)->default(0);
    $table->decimal('penalites', 15, 2)->default(0);
    $table->decimal('tva', 15, 2)->default(0);
    $table->decimal('net_a_payer', 15, 2)->default(0);
    $table->enum('statut', ['brouillon','validee','transmise','approuvee','facturee','rejetee'])->default('brouillon')->index();
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->unique(['projet_id', 'numero']);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_situations');
    }
};
