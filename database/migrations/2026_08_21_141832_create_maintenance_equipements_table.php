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
        Schema::create('maintenance_equipements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('equipement_id')->nullable();
            $table->enum('type', ['preventive', 'corrective']);
            $table->string('description');
            $table->date('date');
            $table->decimal('cout', 12, 2)->default(0);
            $table->string('effectue_par')->nullable(); // atelier interne ou prestataire
            $table->decimal('compteur_heures', 12, 2)->nullable();
            $table->date('prochaine_maintenance_date')->nullable();

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_equipements');
    }
};
