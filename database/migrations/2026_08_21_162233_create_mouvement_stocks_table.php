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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrepot_id')->nullable();
            $table->unsignedBigInteger('materiau_id')->nullable();
            $table->enum('type', ['entree', 'sortie', 'transfert', 'ajustement']);
            $table->decimal('quantite', 14, 3);
            $table->string('type_reference')->nullable(); // delivery, stock_transfer, task, ajustement_inventaire...
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('projet_id')->nullable()->constrained('projets')->nullOnDelete();
            $table->foreignId('effectue_par')->nullable()->constrained('employes')->nullOnDelete();
            $table->date('date');
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
        Schema::dropIfExists('mouvement_stocks');
    }
};
