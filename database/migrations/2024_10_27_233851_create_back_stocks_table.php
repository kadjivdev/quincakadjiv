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
        Schema::create('back_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->double('montant_total');
            $table->foreignId('from_magasin_id')->constrained('magasins')->onDelete('cascade');
            $table->foreignId('to_magasin_id')->constrained('magasins')->onDelete('cascade');
            $table->dateTime('date_op');
            $table->text('observation')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('validator')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('validate_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_stocks');
    }
};
