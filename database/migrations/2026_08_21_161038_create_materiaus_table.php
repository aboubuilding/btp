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
        Schema::create('materiaus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->nullable()->constrained('categories_materiaux')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('nom');
            $table->string('unite'); // sac, m3, kg, tonne, unite, ml...
            $table->decimal('prix_unitaire', 12, 2)->default(0);
            $table->decimal('seuil_alerte_stock_min', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiaus');
    }
};
