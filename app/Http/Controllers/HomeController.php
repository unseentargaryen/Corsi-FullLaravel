<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Subcategory;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::where('visible', '=', 1)->get();
        $subcategories = [];
        $courses = [];
        foreach ($categories as $category) {
            foreach ($category->subcategories()->get() as $subcategory) {
                if ($subcategory->visible) {
                    $subcategories [] = $subcategory;
                    foreach ($subcategory->courses()->get() as $course) {
                        if ($course->visible) {
                            $courses  [] = $course;
                        }
                    }
                }
            }
        }

        return view('home', ['categories' => $categories, 'subcategories' => $subcategories, 'courses' => $courses]);
    }
}
