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
        Schema::create('article_point_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_vente_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('qte_stock')->default(0);
            $table->double('stock_normalise')->default(0);
            $table->double('prix_special')->default(0);
            $table->double('prix_revendeur')->default(0);
            $table->double('prix_particulier')->default(0);
            $table->double('prix_btp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_point_ventes');
    }
};
