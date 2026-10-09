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
    $table->unsignedBigInteger('employee_id')->index();
    $table->enum('type', ['diplome','habilitation','certificat_medical','autre']);
    $table->string('nom');
    $table->string('chemin_fichier')->nullable();
    $table->date('date_expiration')->nullable();
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
