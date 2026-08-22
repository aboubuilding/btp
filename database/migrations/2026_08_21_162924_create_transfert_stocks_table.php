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
        Schema::create('transfert_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrepot_source_id')->nullable();
            $table->unsignedBigInteger('entrepot_destination_id')->nullable();
            $table->unsignedBigInteger('materiau_id')->nullable();
            $table->decimal('quantite', 14, 3);
            $table->foreignId('demande_par')->nullable()->constrained('employes')->nullOnDelete();
            $table->foreignId('approuve_par')->nullable()->constrained('employes')->nullOnDelete();
            $table->date('date_transfert');
            $table->enum('statut', ['en_attente', 'approuve', 'effectue', 'rejete'])->default('en_attente');
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_stocks');
    }
};
