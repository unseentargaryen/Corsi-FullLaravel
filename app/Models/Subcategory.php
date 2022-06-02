<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id'
    ];

    public function categories(){
        return $this->hasOne(Category::class);
    }

    public function courses(){
        return $this->hasMany(Course::class);
    }
}
