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
        Schema::table('sport_class_enrollments', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropUnique('class_enrollments_class_id_member_id_unique');
            $table->renameColumn('class_id', 'sport_class_id');
            $table->foreign('sport_class_id')->references('id')->on('sport_classes')->onDelete('cascade');
            $table->unique(['sport_class_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sport_class_enrollments', function (Blueprint $table) {
            $table->dropForeign(['sport_class_id']);
            $table->dropUnique(['sport_class_id', 'member_id']);
            $table->renameColumn('sport_class_id', 'class_id');
            $table->foreign('class_id')->references('id')->on('sport_classes')->onDelete('cascade');
            $table->unique(['class_id', 'member_id'], 'class_enrollments_class_id_member_id_unique');
        });
    }
};
