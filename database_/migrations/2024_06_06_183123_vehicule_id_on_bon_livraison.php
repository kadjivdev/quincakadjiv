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
        Schema::table('bon_livraisons', function (Blueprint $table) {
            $table->foreignId('vehicule_id')->nullable()->default(1)->constrained('vehicules')->onUpdate('cascade')->onDelete('cascade');            
            if (Schema::hasColumn('bon_livraisons', 'num_vehicule')) {
                $table->dropColumn('num_vehicule');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bon_livraisons', function (Blueprint $table) {
            $table->dropForeign(['vehicule_id']);
            $table->dropColumn('vehicule_id');
        });
    }
};
