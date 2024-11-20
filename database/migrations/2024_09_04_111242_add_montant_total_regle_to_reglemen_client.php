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
        Schema::table('reglement_clients', function (Blueprint $table) {
            $table->double('montant_total_regle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reglement_clients', function (Blueprint $table) {
            $table->dropColumn('montant_total_regle');
        });
    }
};
