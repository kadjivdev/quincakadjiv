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
        Schema::table('livraison_vente_magasins', function (Blueprint $table) {
            $table->foreignId('bon_livraison_vente_comptant_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('unite_mesure_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('prix_unit')->nullable()->default(null);
            $table->text('comment')->nullable()->default(null);
            $table->timestamp('comment_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
