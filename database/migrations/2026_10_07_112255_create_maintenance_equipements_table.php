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
       Schema::create('maintenance_equipements', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipement_id')->index();
    $table->enum('type', ['preventive','corrective']);
    $table->date('date_intervention');
    $table->decimal('cout', 15, 2)->default(0);
    $table->string('intervenant')->nullable();
    $table->decimal('compteur', 12, 2)->nullable();
    $table->date('prochaine_echeance')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_equipements');
    }
};
