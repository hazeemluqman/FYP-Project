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
    Schema::table('checkpoints', function (Blueprint $table) {
        $table->string('owner_name')->after('uid');
    });
}

public function down(): void
{
    Schema::table('checkpoints', function (Blueprint $table) {
        $table->dropColumn('owner_name');
    });
}

};