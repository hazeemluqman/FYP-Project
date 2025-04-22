<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/rfid', function (Request $request) {
    $request->validate([
        'uid' => 'required|string',
        'owner_name' => 'required|string',
    ]);

    DB::table('rfids')->insert([
        'uid' => $request->uid,
        'owner_name' => $request->owner_name,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'RFID stored successfully'], 200);
});