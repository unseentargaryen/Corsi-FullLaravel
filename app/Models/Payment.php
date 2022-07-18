<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class Payment extends Model
{
    use HasFactory;

    public function booking(){
        return $this->hasOne(Booking::class);
    }
}
