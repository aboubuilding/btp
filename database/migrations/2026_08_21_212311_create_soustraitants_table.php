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
        Schema::create('soustraitants', function (Blueprint $table) {
            $table->id();

            $table->string('nom_entreprise');
            $table->string('personne_contact')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('specialite')->nullable(); // electricite, plomberie, etancheite, menuiserie...
            $table->unsignedTinyInteger('note')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'blackliste'])->default('actif');

            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->softDeletes();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soustraitants');
    }
};
