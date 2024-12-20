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
        Schema::table('facture_fournisseurs', function (Blueprint $table) {
            $table->float('tva')->nullable();
            $table->float('aib')->nullable();
            $table->float('autres_frais')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facture_fournisseurs', function (Blueprint $table) {
            //
        });
    }
};
