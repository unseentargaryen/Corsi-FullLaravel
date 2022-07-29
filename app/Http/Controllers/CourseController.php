<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseImage;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $subcategories = Subcategory::all();
        $categories = Category::all();

        return view('admin.courses.index', ['courses' => $courses, 'subcategories' => $subcategories, 'categories' => $categories]);
    }


    public function all()
    {
        $courses = Course::all();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found '
            ], 404);
        }

        return view('show_course', ['course' => $course]);

    }

    public function showAdmin($id)
    {

        $course = Course::find($id);
        $subcategories = Subcategory::all();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found '
            ], 404);
        }
        $lessons = $course->lessons()->get();

        return view('admin.courses.show', ['course' => $course, 'subcategories' => $subcategories,'lessons' => $lessons]);
    }

    public function get($id)
    {

        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found '
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course->toArray()
        ], 200);
    }

    public function getBySubcategoryId($subcategory_id)
    {
        $subcategory = Subcategory::all()->find($subcategory_id);
        $courses = $subcategory->courses;

        if (!$courses) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found '
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $courses->toArray()
        ], 200);
    }

    public function create(Request $request)
    {
        Log::info($request);

        $this->validate($request, [
            'name' => 'required',
            'subcategory_id' => 'required',
            'price' => 'required',
        ]);
        $course = new Course();
        $course->name = $request->name;
        $course->description = $request->description;
        $course->price = $request->price;
        $course->subcategory_id = $request->subcategory_id;

        if ($course->save($course->toArray()))
            return response()->json([
                'success' => true,
                'data' => $course->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Course not added'
            ], 500);
    }

    public function edit(Request $request, $course_id)
    {

        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'visible' => 'required',
            'subcategory_id' => 'exists:subcategories,id',
        ]);

        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $course->visible = $request->visible;
        $updated = $course->fill($request->all())->save();


        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Course can not be updated'
            ], 500);
    }

    public function getCover($course_id)
    {
        $course = Course::all()->find($course_id);
        $image = $course->cover_filename;

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Cover not found '
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image
        ], 200);
    }

    public function setCover(Request $request, $course_id)
    {
        $file = $request->file;
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('courses_images'), $filename);

        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $course->cover_filename = $filename;
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Cover updated'
        ], 200);
    }

    public function getImages($course_id)
    {
        $course = Course::all()->find($course_id);
        $images = $course->images;

        if (!$images) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found '
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $images->toArray()
        ], 200);
    }


    public function addImage(Request $request, $course_id)
    {

        $file = $request->file;
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('courses_images'), $filename);

        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $courseImage = new CourseImage();
        $courseImage->course_id = $course->id;
        $courseImage->filename = $filename;
        $courseImage->created_at = Carbon::now();
        $courseImage->updated_at = Carbon::now();

        $courseImage = $courseImage->save();

        if ($courseImage) {
            return response()->json([
                'success' => true,
                'message' => 'Immagine aggiunta'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Errore'
        ], 501);
    }

    public function removeCourseImage(Request $request)
    {

        if (!$request->filename) {
            return response()->json([
                'success' => false,
                'message' => 'Need a filename '
            ], 422);
        }

        $image = CourseImage::where('filename', '=', $request->filename);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found '
            ], 404);
        }

        $image->delete();
        File::delete(public_path('courses_images') . "/" . $request->filename);

        return response()->json([
            'success' => true,
        ], 200);
    }

}
