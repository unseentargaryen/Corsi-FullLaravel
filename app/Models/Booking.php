<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
