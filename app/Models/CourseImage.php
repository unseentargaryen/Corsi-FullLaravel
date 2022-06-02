<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'src'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
