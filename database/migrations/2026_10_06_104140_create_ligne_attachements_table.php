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
        Schema::create('ligne_attachements', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('attachement_id')->index();
    $table->unsignedBigInteger('ligne_devis_id');
    $table->decimal('quantite_periode', 14, 3)->default(0);
    $table->decimal('quantite_cumulee', 14, 3)->default(0);
    $table->text('observation')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_attachements');
    }
};
