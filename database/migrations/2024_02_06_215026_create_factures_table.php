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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_facture');
            $table->float('montant_facture', 30,3)->default(0);
            $table->string('num_facture');
            $table->float('taux_remise')->default(0);
            $table->float('tva')->default(0);
            $table->float('aib')->default(0);
            $table->double('montant_total', 30,3)->default(0);
            $table->double('montant_regle', 30,3)->default(0);
            $table->string('statut');
            $table->string('client_facture');
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
        Schema::dropIfExists('factures');
    }
};
