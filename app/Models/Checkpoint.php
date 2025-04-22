<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'owner_name', 'checkpoint', 'last_tap_in'];

    // Relationship with Rfid model
    public function rfid()
    {
        return $this->belongsTo(Rfid::class, 'uid', 'uid');
    }
}