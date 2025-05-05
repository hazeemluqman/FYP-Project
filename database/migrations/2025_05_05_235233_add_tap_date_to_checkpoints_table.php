<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Checkpoint;

return new class extends Migration
{
    public function up()
    {
        // First add the column as nullable
        Schema::table('checkpoints', function (Blueprint $table) {
            $table->date('tap_date')->nullable()->after('last_tap_in');
        });

        // Then populate existing records with proper dates
        Checkpoint::chunk(200, function ($checkpoints) {
            foreach ($checkpoints as $checkpoint) {
                if ($checkpoint->last_tap_in) {
                    $checkpoint->tap_date = $checkpoint->last_tap_in->format('Y-m-d');
                    $checkpoint->save();
                }
            }
        });

        // Finally modify the column to be not nullable
        Schema::table('checkpoints', function (Blueprint $table) {
            $table->date('tap_date')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('checkpoints', function (Blueprint $table) {
            $table->dropColumn('tap_date');
        });
    }
};