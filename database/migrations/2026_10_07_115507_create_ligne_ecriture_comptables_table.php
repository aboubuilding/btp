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
        Schema::create('ligne_ecriture_comptables', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('ecriture_comptable_id')->index();
    $table->unsignedBigInteger('plan_comptable_id');
    $table->decimal('debit', 15, 2)->default(0);
    $table->decimal('credit', 15, 2)->default(0);
    $table->string('libelle')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_ecriture_comptables');
    }
};
