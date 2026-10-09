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
        Schema::create('avenant_marches', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('marche_id')->index();
    $table->string('numero')->nullable();
    $table->string('objet');
    $table->decimal('montant', 15, 2)->default(0);
    $table->unsignedSmallInteger('delai_jours')->nullable();
    $table->date('date_signature')->nullable();
    $table->boolean('est_signe')->default(false);
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avenant_marches');
    }
};
