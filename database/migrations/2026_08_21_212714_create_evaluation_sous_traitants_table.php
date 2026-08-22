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

            $table->foreignId('sous_traitant_id')->constrained('sous_traitants')->cascadeOnDelete();
            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->foreignId('evaluer_par')->nullable()->constrained('users')->nullOnDelete();
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
