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
        Schema::create('acompte_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->double('montant_acompte', 30, 2)->default(0);
            $table->string('reference')->nullable();
            $table->string('code')->unique();
            $table->foreignId('reglement_client_id')->nullable()->constrained();
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users');
            $table->dateTime('validated_at')->nullable();
            $table->enum('type_reglement', ['Espèce', 'Chèque', 'Virement', 'Autres']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompte_clients');
    }
};
