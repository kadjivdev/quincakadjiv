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
        Schema::create('bon_livraison_vente_comptants', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_livraison');
            $table->foreignId('vente_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('ref_bon')->unique();
            $table->string('chauffeur_id');
            $table->string('vehicule_id');
            $table->string('adr_livraison');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_livraison_vente_comptants');
    }
};
