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
        Schema::create('data_ventes', function (Blueprint $table) {
            $table->id();
            $table->double('montant_facture', 30,2);
            $table->float('taux_remise')->nullable();
            $table->double('montant_total', 30,2);
            $table->double('montant_regle', 30,2)->nullable();
            $table->integer('facture_type_id');
            $table->foreignId('vente_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_ventes');
    }
};
