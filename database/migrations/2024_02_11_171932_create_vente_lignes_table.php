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
        Schema::create('vente_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('qte_cmde', 15,2);
            $table->float('qte_livre', 15,2);
            $table->float('prix_unit', 30,2);
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente_lignes');
    }
};
