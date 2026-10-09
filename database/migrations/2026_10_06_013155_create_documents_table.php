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
        Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('chemin');
    $table->string('mime', 100)->nullable();
    $table->unsignedBigInteger('taille')->nullable();
    $table->string('documentable_type');    // morph
    $table->unsignedBigInteger('documentable_id');
    $table->unsignedBigInteger('uploaded_by')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
    $table->index(['documentable_type', 'documentable_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
