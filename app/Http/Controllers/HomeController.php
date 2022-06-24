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
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $courses = Course::all();

        return view('home', ['categories' => $categories, 'subcategories' => $subcategories, 'courses' => $courses]);
    }
}
