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
        Schema::create('manage_users_table', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('phone');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('pincode');
            $table->string('gender');
            $table->string('dob');
            $table->string('subscription_type');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('starter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_users_table');
    }
};
