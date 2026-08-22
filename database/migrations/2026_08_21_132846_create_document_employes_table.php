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
        Schema::create('document_employes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employe_id')->nullable();
            $table->string('type'); // CNI, diplome, permis, attestation CNSS...
            $table->string('chemin_fichier');
            $table->date('date_expiration')->nullable();
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_employes');
    }
};
