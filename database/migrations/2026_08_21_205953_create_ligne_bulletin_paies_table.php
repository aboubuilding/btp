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
        Schema::create('ligne_bulletin_paies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bulletin_paie_id')->nullable();
            $table->enum('type', ['prime', 'indemnite', 'retenue', 'avance']);
            $table->string('libelle'); // Prime de rendement, Indemnite transport, Avance sur salaire...
            $table->decimal('montant', 12, 2);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_bulletin_paies');
    }
};
