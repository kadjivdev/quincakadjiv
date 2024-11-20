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
        Schema::create('facture_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_facture');
            $table->double('montant_facture', 30,2);
            $table->string('num_facture');
            $table->float('taux_remise')->nullable();
            $table->float('tva')->nullable();
            $table->float('aib')->nullable();
            $table->float('autres_frais')->nullable();
            $table->double('montant_total', 30,2);
            $table->double('montant_regle', 30,2)->nullable();
            $table->string('statut');
            $table->string('client_facture');
            $table->foreignId('facture_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_ventes');
    }
};
