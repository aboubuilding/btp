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
        Schema::create('journal_activites', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable()->index();
    $table->string('action');                     // create, update, validate, delete
    $table->string('objet_type');
    $table->unsignedBigInteger('objet_id');
    $table->json('meta')->nullable();
    $table->string('ip', 45)->nullable();
    $table->timestamp('date_action')->useCurrent();
    $table->index(['objet_type', 'objet_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_activites');
    }
};
