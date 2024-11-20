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
        Schema::create('stock_magasins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magasin_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('qte_stock')->default(0);
            $table->double('qte_reel')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_magasins');
    }
};
