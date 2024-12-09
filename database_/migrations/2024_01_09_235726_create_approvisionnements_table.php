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
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->date('date_livraison');
            $table->float('qte_livre');
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->dateTime('validated_at')->nullable();
            $table->foreignId('ligne_commande_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('ligne_supplement_commande_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('magasin_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};
