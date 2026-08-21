<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('tuteur_prenom')->nullable()->after('adresse');
            $table->string('tuteur_nom')->nullable()->after('tuteur_prenom');
            $table->string('tuteur_telephone', 30)->nullable()->after('tuteur_nom');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['tuteur_prenom', 'tuteur_nom', 'tuteur_telephone']);
        });
    }
};
