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
        Schema::create('livraison_directes', function (Blueprint $table) {
            $table->id();
            $table->date('date_livraison');
            $table->string('ref_livraison')->nullable();
            $table->double('qte_livre', 15,3);
            $table->double('prix_vente', 30,3);
            $table->foreignId('ligne_commande_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('validated_at')->nullable();
            $table->float('remise')->nullable();
            $table->float('tva')->nullable();
            $table->float('aib')->nullable();
            $table->string('num_facture');
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->foreignId('facture_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraison_directes');
    }
};
