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
        Schema::create('bon_livraisons', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_livraison');
            $table->foreignId('devis_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('code_bon')->unique();
            $table->string('chauffeur');
            $table->string('adr_livraison');
            $table->string('num_vehicule');
            $table->string('tel_chauffeur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_livraisons');
    }
};
