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
        Schema::create('equipe_projets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('projet_id')->constrained('projets')->cascadeOnDelete();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->string('role_sur_chantier')->nullable(); // rôle spécifique sur ce chantier
            $table->date('affecte_le');
            $table->date('affecte_jusquau')->nullable();

            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe_projets');
    }
};
