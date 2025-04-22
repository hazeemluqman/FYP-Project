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
    Schema::create('rfids', function (Blueprint $table) {
        $table->id();
        $table->string('owner_name');
        $table->string('uid')->unique();
        $table->string('phone_number')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('rfids');
}

};