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
        Schema::table('reglement_clients', function (Blueprint $table) {
            // Redéfinir la colonne ENUM avec la nouvelle valeur
            $table->enum('type_reglement', ['Espèce', 'Chèque', 'Virement', 'Autres', 'Décharge', 'Momo Marchand', 'Momo Pay', 'Tontine'])
                  ->change();
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