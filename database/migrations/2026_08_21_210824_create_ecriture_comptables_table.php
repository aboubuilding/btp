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
        Schema::create('ecriture_comptables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('exercice_fiscal_id')->nullable();
            $table->string('numero_ecriture')->unique();
            $table->date('date_ecriture');
            $table->string('type_reference')->nullable(); // invoice, expense, payment, payslip...
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('cree_par')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['brouillon', 'valide'])->default('brouillon');
            $table->integer('etat')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecriture_comptables');
    }
};
