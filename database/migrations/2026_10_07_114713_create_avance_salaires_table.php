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
    $table->unsignedBigInteger('employee_id')->index();
    $table->decimal('montant', 15, 2);
    $table->date('date_avance');
    $table->enum('statut', ['demande','approuvee','remboursee','refusee'])->default('demande');
    $table->unsignedBigInteger('approuve_par')->nullable();
    $table->timestamp('approuve_le')->nullable();
    $table->decimal('montant_rembourse', 15, 2)->default(0);
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
