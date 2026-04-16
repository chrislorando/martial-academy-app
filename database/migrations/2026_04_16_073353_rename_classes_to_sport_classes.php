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
        Schema::rename('classes', 'sport_classes');
        Schema::rename('class_enrollments', 'sport_class_enrollments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('sport_classes', 'classes');
        Schema::rename('sport_class_enrollments', 'class_enrollments');
    }
};
