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
        Schema::create('soustraitants', function (Blueprint $table) {
    $table->id();
    $table->string('entreprise');
    $table->string('contact')->nullable();
    $table->string('telephone')->nullable();
    $table->string('email')->nullable();
    $table->string('specialite')->nullable();
    $table->decimal('note', 3, 1)->nullable();
    $table->enum('statut', ['actif','suspendu','blackliste'])->default('actif')->index();
    $table->string('source_reprise', 50)->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soustraitants');
    }
};
