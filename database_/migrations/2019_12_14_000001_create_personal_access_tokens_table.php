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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

             // Supprimer l'index existant
             $table->dropIndex('personal_access_tokens_tokenable_type_tokenable_id_index');

             // Ajouter un nouvel index avec une longueur spécifiée
             $table->index(['tokenable_type', 'tokenable_id'], 'personal_access_tokens_tokenable_type_tokenable_id_index')
                 ->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Annuler la migration en supprimant l'index et en recréant l'index sans spécifier la longueur
            $table->dropIndex('personal_access_tokens_tokenable_type_tokenable_id_index');
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }
};
