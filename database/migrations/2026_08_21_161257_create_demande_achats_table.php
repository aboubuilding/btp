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
        Schema::create('demande_achats', function (Blueprint $table) {
            $table->id();

            $table->string('numero_demande')->unique();
            $table->unsignedBigInteger('projet_id')->nullable();
            $table->unsignedBigInteger('demande_par')->nullable();
            $table->date('date_demande');
            $table->enum('statut', ['en_attente', 'validee', 'rejetee', 'commandee'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_achats');
    }
};
