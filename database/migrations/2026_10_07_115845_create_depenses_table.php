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
        Schema::create('depenses', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->unsignedBigInteger('ligne_budget_id')->nullable();
    $table->unsignedBigInteger('caisse_id')->nullable();
    $table->string('categorie');
    $table->string('description');
    $table->decimal('montant', 15, 2);
    $table->date('date_depense');
    $table->foreignId('paye_par')->nullable()->constrained('employees')->nullOnDelete();
    $table->string('document_justificatif')->nullable();
    $table->enum('statut', ['en_attente','approuve','rejete'])->default('en_attente');
    $table->unsignedBigInteger('approuve_par')->nullable();
    $table->timestamp('approuve_le')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
