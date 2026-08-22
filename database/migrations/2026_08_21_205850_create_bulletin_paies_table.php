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
        Schema::create('bulletin_paies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_paie_id')->nullable();
            $table->unsignedBigInteger('employe_id')->nullable();
            $table->decimal('salaire_base', 12, 2);
            $table->decimal('jours_travailles', 5, 2)->default(0);
            $table->decimal('heures_travaillees', 6, 2)->default(0);
            $table->decimal('heures_supplementaires', 6, 2)->default(0);
            $table->decimal('salaire_brut', 12, 2)->default(0);
            $table->decimal('cnss_employe', 12, 2)->default(0);
            $table->decimal('cnss_employeur', 12, 2)->default(0);
            $table->decimal('impot_revenu', 12, 2)->default(0); // ITS
            $table->decimal('salaire_net', 12, 2)->default(0);
            $table->date('date_paiement')->nullable();
            $table->enum('mode_paiement', ['especes', 'virement', 'mobile_money', 'cheque'])->nullable();
            $table->enum('statut', ['brouillon', 'valide', 'paye'])->default('brouillon');

            $table->integer('etat')->default(1);
            $table->timestamps();
            $table->unique(['periode_paie_id', 'employe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_paies');
    }
};
