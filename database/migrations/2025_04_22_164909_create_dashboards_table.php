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
    Schema::create('checkpoints', function (Blueprint $table) {
        $table->id();
        $table->string('uid');
        $table->string('owner_name')->after('uid');
        $table->string('checkpoint');
        $table->timestamp('last_tap_in')->useCurrent();
        $table->timestamps();

        $table->foreign('uid')->references('uid')->on('rfids')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboards');
    }
};