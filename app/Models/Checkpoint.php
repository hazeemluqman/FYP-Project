<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid', 
        'owner_name', 
        'checkpoint', 
        'last_tap_in',
        'tap_date'  // Added the new field
    ];

    protected $casts = [
        'last_tap_in' => 'datetime',
        'tap_date' => 'date'  // Proper casting for the date field
    ];

    // Relationship with Rfid model
    public function rfid()
    {
        return $this->belongsTo(Rfid::class, 'uid', 'uid');
    }

    // Scope for today's checkpoints
    public function scopeToday($query)
    {
        return $query->whereDate('tap_date', now()->format('Y-m-d'));
    }

    // Scope for checkpoints on a specific date
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('tap_date', $date);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}