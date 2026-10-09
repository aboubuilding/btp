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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('entrepot_id')->index();
    $table->unsignedBigInteger('materiau_id')->index();
    $table->enum('type', ['entree','sortie','transfert_entree','transfert_sortie','ajustement']);
    $table->decimal('quantite', 14, 3);
    $table->decimal('prix_unitaire', 15, 2)->default(0);
    $table->unsignedBigInteger('projet_id')->nullable()->index();    // imputation
    $table->string('reference_type')->nullable();                     // Livraison, Inventaire...
    $table->unsignedBigInteger('reference_id')->nullable();
    $table->date('date_mouvement');
    $table->unsignedBigInteger('enregistre_par')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
