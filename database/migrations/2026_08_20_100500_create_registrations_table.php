<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('numero_inscription', 40)->nullable()->unique();
            $table->string('statut', 30)->default('en_attente')->index();
            $table->timestamp('date_soumission')->nullable();
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_admin')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
