<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    // Add the new fields to the $fillable array
    protected $fillable = [
        'owner_name',
        'uid',
        'phone_number',
        'gender',
        'address',
        'birthday',
        'emergency_contact',
        'email',
    ];

    // Relationship with Checkpoints
    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class, 'uid', 'uid');
    }
}