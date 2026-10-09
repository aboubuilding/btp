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
        Schema::create('lot_devis', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('devis_id')->nullable()->index();
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->string('numero')->nullable();              // 1, 1.1
    $table->string('libelle');
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
        Schema::dropIfExists('lot_devis');
    }
};
