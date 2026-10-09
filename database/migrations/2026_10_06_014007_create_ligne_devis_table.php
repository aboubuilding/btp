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
       Schema::create('ligne_devis', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('devis_id')->nullable()->index();
    $table->unsignedBigInteger('lot_devis_id')->nullable();
    $table->string('numero_prix')->nullable();         // n° BPU
    $table->string('designation');
    $table->string('unite');                           // m3, m2, kg, u, ens, ff
    $table->decimal('quantite', 14, 3)->default(0);
    $table->decimal('debourse_sec_unitaire', 15, 2)->default(0);
    $table->decimal('prix_unitaire', 15, 2)->default(0);
    $table->decimal('montant', 15, 2)->default(0);
    $table->unsignedInteger('ordre')->default(0);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_devis');
    }
};
