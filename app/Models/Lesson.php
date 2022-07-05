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
        return $this->hasOne(Course::class);
    }
}
