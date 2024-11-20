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
        Schema::create('facture_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_facture');
            $table->double('montant_facture', 30,2)->default(0);
            $table->string('ref_facture');
            $table->float('taux_remise')->default(0);
            $table->double('montant_total', 30,2)->default(0);
            $table->double('montant_regle', 30,2)->default(0);
            $table->string('statut');
            $table->foreignId('fournisseur_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('facture_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_fournisseurs');
    }
};
