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
        Schema::table('data_ventes', function (Blueprint $table) {
            $table->double('tva', 8,2);
            $table->double('aib', 8,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_ventes', function (Blueprint $table) {
            $table->dropColumn('tva');
            $table->dropColumn('aib');
        });
    }
};
