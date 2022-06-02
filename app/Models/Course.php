<?php

namespace App\Models;

use App\Http\Controllers\CourseController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'subcategory_id',
    ];

    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }

    public function images(){
        return $this->hasMany(CourseImage::class);
    }

}
