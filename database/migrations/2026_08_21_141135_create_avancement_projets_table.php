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
        Schema::create('avancement_projets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->date('date_rapport');
            $table->foreignId('rapporte_par')->constrained('employes')->cascadeOnDelete();
            $table->unsignedTinyInteger('pourcentage_avancement');
            $table->string('meteo')->nullable();
            $table->text('travaux_realises')->nullable();
            $table->text('problemes')->nullable();
            $table->unsignedInteger('ouvriers_presents')->nullable();

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avancement_projets');
    }
};
