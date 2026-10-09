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
        Schema::create('evaluation_sous_traitants', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('sous_traitant_id')->index();
    $table->unsignedBigInteger('projet_id')->index();
    $table->unsignedTinyInteger('note_qualite')->nullable();
    $table->unsignedTinyInteger('note_delai')->nullable();
    $table->unsignedTinyInteger('note_securite')->nullable();
    $table->text('commentaire')->nullable();
    $table->unsignedBigInteger('evalue_par')->nullable();
    $table->date('date_evaluation');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_sous_traitants');
    }
};
