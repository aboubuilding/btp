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
        Schema::create('causerie_securites', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('projet_id')->nullable()->index();
    $table->date('date');
    $table->string('theme');
    $table->unsignedSmallInteger('nombre_participants')->default(0);
    $table->unsignedBigInteger('anime_par')->nullable();
    $table->integer('etat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('causerie_securites');
    }
};
