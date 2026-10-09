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
        Schema::create('demande_achats', function (Blueprint $table) {
    $table->id();
    $table->string('numero')->unique();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->unsignedBigInteger('demandeur_id')->nullable();
    $table->date('date_demande');
    $table->enum('statut', ['en_attente','validee','rejetee','commandee'])->default('en_attente')->index();
    $table->text('observation')->nullable();
    $table->unsignedBigInteger('valide_par')->nullable();
    $table->timestamp('valide_le')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_achats');
    }
};
