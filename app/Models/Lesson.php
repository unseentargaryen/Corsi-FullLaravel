<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function pendingBookings(){
        return $this->hasMany(PendingBooking::class);
    }
}
