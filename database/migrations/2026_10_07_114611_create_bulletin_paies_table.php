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
       Schema::create('bulletin_paies', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('periode_paie_id')->index();
    $table->unsignedBigInteger('employee_id')->index();
    $table->decimal('salaire_base', 15, 2)->default(0);
    $table->decimal('heures_travaillees', 8, 2)->default(0);
    $table->decimal('heures_supplementaires', 8, 2)->default(0);
    $table->decimal('montant_heures_sup', 15, 2)->default(0);
    $table->decimal('primes', 15, 2)->default(0);
    $table->decimal('indemnites', 15, 2)->default(0);
    $table->decimal('brut', 15, 2)->default(0);
    $table->decimal('cotisations_salariales', 15, 2)->default(0);
    $table->decimal('cotisations_patronales', 15, 2)->default(0);
    $table->decimal('impot_revenu', 15, 2)->default(0);
    $table->decimal('avances_deduites', 15, 2)->default(0);
    $table->decimal('net_a_payer', 15, 2)->default(0);
    $table->enum('statut', ['brouillon','valide','paye'])->default('brouillon');
    $table->unique(['periode_paie_id', 'employee_id']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_paies');
    }
};
