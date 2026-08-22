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
        Schema::create('entrepots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('projet_id')->nullable()->constrained('projets')->nullOnDelete(); // null = dépôt central
            $table->string('nom');
            $table->string('emplacement')->nullable();
            $table->foreignId('responsable_id')->nullable()->constrained('employes')->nullOnDelete();
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrepots');
    }
};
