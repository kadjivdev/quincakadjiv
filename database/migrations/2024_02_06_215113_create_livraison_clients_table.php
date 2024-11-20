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
        Schema::create('livraison_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_livraison_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('qte_livre');
            $table->float('prix_unit', 30,3);
            $table->foreignId('magasin_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->dateTime('validated_at')->nullable();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraison_clients');
    }
};
