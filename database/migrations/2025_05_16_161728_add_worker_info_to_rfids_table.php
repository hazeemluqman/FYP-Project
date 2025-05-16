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
        Schema::table('rfids', function (Blueprint $table) {
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->date('birthday')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfids', function (Blueprint $table) {
            $table->dropColumn(['gender', 'address', 'birthday', 'emergency_contact', 'email']);
        });
    }
};