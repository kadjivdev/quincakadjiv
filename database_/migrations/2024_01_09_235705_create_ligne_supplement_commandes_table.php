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
        Schema::create('ligne_supplement_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplement_commande_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('qte_cmde');
            $table->float('prix_unit');
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_supplement_commandes');
    }
};
