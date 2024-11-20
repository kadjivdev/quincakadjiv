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
        Schema::create('livraison_pvs', function (Blueprint $table) {

            $table->id();
            $table->date('date_liv');
            $table->string('ref_liv');
            $table->double('cout_revient');
            $table->foreignId('chauffeur_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('vehicule_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('magasin_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraison_pv');
    }
};
