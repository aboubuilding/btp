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
        Schema::create('type_conges', function (Blueprint $table) {
            $table->id();

            $table->string('nom'); // Congé payé, Maladie, Sans solde, Maternité...
            $table->integer('jours_autorises_par_an')->nullable();
            $table->boolean('est_paye')->default(true);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_conges');
    }
};
