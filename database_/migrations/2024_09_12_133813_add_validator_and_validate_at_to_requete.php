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
        Schema::table('requetes', function (Blueprint $table) {
            $table->foreignId('validator')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade')->after('client_id');
            $table->timestamp('validate_at')->nullable()->after('validator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requetes', function (Blueprint $table) {
            $table->dropColumn('validate_at');
            $table->dropForeign(['validator']);
            $table->dropColumn('validator');
        });
    }
};
