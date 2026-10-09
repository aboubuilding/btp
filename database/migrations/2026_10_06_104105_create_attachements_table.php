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
       Schema::create('attachements', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->index();
    $table->unsignedSmallInteger('numero');
    $table->date('periode_debut');
    $table->date('periode_fin');
    $table->unsignedBigInteger('etabli_par')->nullable();
    $table->enum('statut', ['brouillon','valide','contradictoire'])->default('brouillon');
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
        Schema::dropIfExists('attachements');
    }
};
