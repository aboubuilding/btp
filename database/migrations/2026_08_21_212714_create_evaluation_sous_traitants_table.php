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

            $table->unsignedBigInteger('sous_traitant_id')->nullable();
            $table->unsignedBigInteger('projet_id')->nullable();
            $table->unsignedBigInteger('evaluer_par')->nullable();
            $table->unsignedTinyInteger('note_qualite'); // /5
            $table->unsignedTinyInteger('note_delai'); // /5
            $table->unsignedTinyInteger('note_securite'); // /5
            $table->text('commentaires')->nullable();
            $table->date('date_evaluation');

            $table->integer('etat')->default(1);
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
