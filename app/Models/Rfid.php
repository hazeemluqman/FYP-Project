<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    protected $fillable = ['owner_name', 'uid', 'phone_number'];

    // Relationship with Checkpoints
    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class, 'uid', 'uid');
    }
}