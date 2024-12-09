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
        Schema::table('acomte', function (Blueprint $table) {
            //
            Schema::table('acompte_clients', function (Blueprint $table) {
            //
             $table->string('observation_acompte_client')->nullable();
          });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acomte', function (Blueprint $table) {
            //
        });
    }
};
