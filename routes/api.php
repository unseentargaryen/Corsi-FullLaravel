<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::post('/login-script', 'App\Http\Controllers\Auth\UserAuthController@login')->name('login');
Route::post('/register-script', 'App\Http\Controllers\Auth\UserAuthController@register')->name('register-script');

Route::post('/verify-token', 'App\Http\Controllers\Auth\UserAuthController@verifyToken');
Route::post('/reset-password-request', 'App\Http\Controllers\Auth\UserAuthController@resetPasswordRequest');
Route::post('/reset-password', 'App\Http\Controllers\Auth\UserAuthController@resetPassword')->middleware('guest')->name('password.update');

Route::post('/can-access','App\Http\Controllers\Auth\UserAuthController@getUserFromBearer');

Route::get('/courses', "App\Http\Controllers\CourseController@index");
Route::get('/courses/{id}', "App\Http\Controllers\CourseController@show");
Route::get('/courses/get-by-subcategory-id/{subcategory_id}', "App\Http\Controllers\CourseController@getBySubcategoryId");
Route::get('/courses/get-images/{course_id}', "App\Http\Controllers\CourseController@getImages");
Route::get('/courses/get-cover/{course_id}', "App\Http\Controllers\CourseController@getCover");
Route::post('/courses', "App\Http\Controllers\CategoryController@store")->middleware('auth:api');
Route::patch('/courses', "App\Http\Controllers\CategoryController@update")->middleware('auth:api');
Route::delete('/courses', "App\Http\Controllers\CategoryController@destroy")->middleware('auth:api');

Route::get('/categories', "App\Http\Controllers\CategoryController@index");
Route::get('/categories/{id}', "App\Http\Controllers\CategoryController@show");
Route::post('/categories', "App\Http\Controllers\CategoryController@store")->middleware('auth:api');
Route::patch('/categories', "App\Http\Controllers\CategoryController@update")->middleware('auth:api');
Route::delete('/categories', "App\Http\Controllers\CategoryController@destroy")->middleware('auth:api');

Route::get('/subcategories', "App\Http\Controllers\SubcategoryController@index");
Route::get('/subcategories/{id}', "App\Http\Controllers\SubcategoryController@show");
Route::post('/subcategories', "App\Http\Controllers\SubcategoryController@store")->middleware('auth:api');
Route::patch('/subcategories', "App\Http\Controllers\SubcategoryController@update")->middleware('auth:api');
Route::delete('/subcategories', "App\Http\Controllers\SubcategoryController@destroy")->middleware('auth:api');
