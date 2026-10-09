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
        Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->string('matricule')->unique();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('nom');
    $table->string('prenom');
    $table->date('date_naissance')->nullable();
    $table->string('piece_identite')->nullable();
    $table->string('numero_cnss')->nullable();
    $table->date('date_embauche')->nullable();
    $table->unsignedBigInteger('poste_id')->nullable();
    $table->unsignedBigInteger('departement_id')->nullable();
    $table->enum('type_contrat', ['cdi','cdd','journalier','stage','prestataire'])->default('cdi');
    $table->decimal('salaire_base', 15, 2)->default(0);
    $table->enum('mode_paiement', ['especes','cheque','virement','mobile_money'])->default('especes');
    $table->string('telephone')->nullable();
    $table->string('contact_urgence')->nullable();
    $table->string('source_reprise', 50)->nullable();
    $table->enum('statut', ['actif','inactif','suspendu'])->default('actif');
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
