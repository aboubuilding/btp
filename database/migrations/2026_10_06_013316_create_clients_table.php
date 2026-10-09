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
        Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('nom');                             // raison sociale
    $table->enum('type', ['particulier','entreprise','public'])->index();
    $table->string('contact')->nullable();
    $table->string('telephone')->nullable();
    $table->string('email')->nullable();
    $table->string('adresse')->nullable();
    $table->string('nif')->nullable();
    $table->string('source_reprise', 50)->nullable();
    $table->unsignedInteger('ligne_reprise')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
