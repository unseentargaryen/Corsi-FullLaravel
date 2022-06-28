<?php

use Illuminate\Support\Facades\Route;

Route::get('/courses', "App\Http\Controllers\CourseController@index");
Route::get('/courses/{id}', "App\Http\Controllers\CourseController@show");
Route::get('/courses/get-by-subcategory-id/{subcategory_id}', "App\Http\Controllers\CourseController@getBySubcategoryId");
Route::get('/courses/get-images/{course_id}', "App\Http\Controllers\CourseController@getImages");
Route::get('/courses/get-cover/{course_id}', "App\Http\Controllers\CourseController@getCover");
Route::post('/courses', "App\Http\Controllers\CourseController@store")->middleware('auth:api');
Route::patch('/courses', "App\Http\Controllers\CourseController@update")->middleware('auth:api');
Route::delete('/courses', "App\Http\Controllers\CourseController@destroy")->middleware('auth:api');

