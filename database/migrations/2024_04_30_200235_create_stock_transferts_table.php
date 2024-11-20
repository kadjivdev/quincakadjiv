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
        Schema::create('stock_transferts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('magasin_depart_id');
            $table->foreign('magasin_depart_id')->references('id')->on('magasins');
            $table->unsignedBigInteger('magasin_dest_id');
            $table->foreign('magasin_dest_id')->references('id')->on('magasins');
                      $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->float('qte_transfert');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->dateTime('validated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transferts');
    }
};
