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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->foreign('sport_class_id')->references('id')->on('sport_classes')->onDelete('cascade');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['class_id', 'member_id', 'date']);
            $table->unique(['sport_class_id', 'member_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['sport_class_id']);
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['sport_class_id', 'member_id', 'date']);
            $table->unique(['class_id', 'member_id', 'date']);
        });
    }
};
