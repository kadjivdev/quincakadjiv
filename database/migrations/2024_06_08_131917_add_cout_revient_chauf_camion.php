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
        Schema::table('approvisionnements', function (Blueprint $table) {
            $table->foreignId('vehicule_id')->nullable()->default(1)->constrained('vehicules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('chauffeur_id')->nullable()->default(1)->constrained('chauffeurs')->onUpdate('cascade')->onDelete('cascade');
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
