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
        Schema::create('niveau_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entrepot_id')->constrained('entrepots')->cascadeOnDelete();
            $table->foreignId('materiau_id')->constrained('materiaux')->cascadeOnDelete();
            $table->decimal('quantite', 14, 3)->default(0);
            $table->timestamps();
            $table->unique(['entrepot_id', 'materiau_id']);
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_stocks');
    }
};
