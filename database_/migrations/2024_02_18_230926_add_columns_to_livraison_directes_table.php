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
        Schema::table('livraison_directes', function (Blueprint $table) {
            $table->double('montant_facture', 30,2)->default(0);
            $table->double('montant_total', 30,2)->default(0);
            $table->double('montant_regle', 30,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livraison_directes', function (Blueprint $table) {
            //
        });
    }
};
