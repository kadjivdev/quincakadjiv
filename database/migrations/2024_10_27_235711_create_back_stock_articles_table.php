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
        Schema::create('back_stock_articles', function (Blueprint $table) {
            $table->id();
            $table->double('qte_back');
            $table->double('qte_vrai');
            $table->double('prix_unit');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('unite_mesure_id')->constrained()->onDelete('cascade');
            $table->foreignId('back_stock_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_stock_articles');
    }
};
