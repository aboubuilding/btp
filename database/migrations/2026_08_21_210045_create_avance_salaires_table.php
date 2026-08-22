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
        Schema::create('avance_salaires', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->decimal('montant', 12, 2);
            $table->date('date_demande');
            $table->foreignId('approuve_par')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['en_attente', 'approuve', 'refuse'])->default('en_attente');
            $table->enum('statut_remboursement', ['non_rembourse', 'partiel', 'solde'])->default('non_rembourse');
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance_salaires');
    }
};
