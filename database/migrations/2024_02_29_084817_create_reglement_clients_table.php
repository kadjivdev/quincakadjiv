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
        Schema::create('reglement_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('livraison_directe_id')->nullable()->constrained();
            $table->foreignId('facture_ancienne_id')->nullable()->constrained();
            $table->foreignId('facture_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('montant_regle', 30,2)->default(0);
            $table->dateTime('date_reglement');
            $table->string('code')->unique();
            $table->string('reference')->unique();
            $table->string('preuve_decharge')->nullable();
            $table->enum('type_reglement', ['Espèce', 'Chèque', 'Virement', 'Autres', 'Décharge']);
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->dateTime('validated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglement_clients');
    }
};
