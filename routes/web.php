<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); //ha un middleware auth nella classe
Route::get('/home', function () {
    return redirect('/');
}); //l muert di auth scaffold

Auth::routes();

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories-dashboard');
    Route::get('subcategories', [SubcategoryController::class, 'index'])->name('subcategories-dashboard');
    Route::get('courses', [CourseController::class, 'index'])->name('courses-dashboard');
    Route::get('courses/show/{id}', [CourseController::class, 'showAdmin'])->name('courses-dashboard-show');
});

Route::get('categories', "App\Http\Controllers\CategoryController@all")->name('categories-list');
Route::get('categories/{id}', "App\Http\Controllers\CategoryController@show")->name('categories-show');
Route::post('categories/create', "App\Http\Controllers\CategoryController@create")->middleware(['auth', 'admin'])->name('categories-create');
Route::post('categories/edit', "App\Http\Controllers\CategoryController@edit")->middleware(['auth', 'admin'])->name('categories-edit');
Route::match(['PATCH', 'DELETE'], '/categories/toggle-visibility', "App\Http\Controllers\CategoryController@toggleVisibility")->middleware(['auth', 'admin'])->name('categories-toggle-visibility');

Route::get('subcategories', "App\Http\Controllers\SubcategoryController@all")->name('subcategories-list');
Route::get('subcategories/{id}', "App\Http\Controllers\SubcategoryController@show")->name('subcategories-show');
Route::post('subcategories/create', "App\Http\Controllers\SubcategoryController@create")->middleware(['auth', 'admin'])->name('subcategories-create');
Route::post('subcategories/edit', "App\Http\Controllers\SubcategoryController@edit")->middleware(['auth', 'admin'])->name('subcategories-edit');
Route::match(['PATCH', 'DELETE'], 'subcategories/toggle-visibility', "App\Http\Controllers\SubcategoryController@toggleVisibility")->middleware(['auth', 'admin'])->name('subcategories-toggle-visibility');


Route::get('/courses/all', "App\Http\Controllers\CourseController@all")->name('courses-all');
Route::post('/courses/create', "App\Http\Controllers\CourseController@create")->middleware(['auth', 'admin'])->name('courses-create');
Route::post('/courses/edit/{id}', "App\Http\Controllers\CourseController@edit")->middleware(['auth', 'admin'])->name('course-edit');

Route::get('/courses/get/{id}', "App\Http\Controllers\CourseController@get")->middleware(['auth', 'admin'])->name('courses-get');
Route::get('/courses/get-by-subcategory-id/{subcategory_id}', "App\Http\Controllers\CourseController@getBySubcategoryId");


Route::get('/courses/{id}', "App\Http\Controllers\CourseController@show")->name('courses-show');

Route::post('/courses/remove-course-image', "App\Http\Controllers\CourseController@removeCourseImage")->middleware(['auth', 'admin'])->name('remove-course-image');

Route::get('/courses/get-cover/{course_id}', "App\Http\Controllers\CourseController@getCover")->name('get-course-cover');
Route::post('/courses/set-cover/{course_id}', "App\Http\Controllers\CourseController@setCover")->middleware(['auth', 'admin'])->name('set-course-cover');
Route::post('/courses/add-image/{course_id}', "App\Http\Controllers\CourseController@addImage")->middleware(['auth', 'admin'])->name('add-course-image');
Route::get('/courses/get-images/{course_id}', "App\Http\Controllers\CourseController@getImages")->name('get-course-images');

Route::get('/courses/get-lessons/{course_id}/', "App\Http\Controllers\LessonController@getLessonsByCourse")->name('get-course-lessons');

Route::get('/lessons/all', "App\Http\Controllers\LessonController@all")->name('lessons-all');
Route::post('/lessons/create', "App\Http\Controllers\LessonController@create")->name('lessons-create');
