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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('subscription_type', ['Monthly', 'Session Based']);
            $table->integer('subscription_value');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('sessions_remaining')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Frozen', 'Completed'])->default('Active');
            $table->timestamps();

            $table->unique(['member_id', 'status'])->where('status', 'Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
