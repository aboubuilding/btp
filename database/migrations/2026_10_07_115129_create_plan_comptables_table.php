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
        Schema::create('plan_comptables', function (Blueprint $table) {
    $table->id();
    $table->string('numero')->unique();
    $table->string('libelle');
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->enum('classe', ['1','2','3','4','5','6','7','8','9'])->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_comptables');
    }
};
