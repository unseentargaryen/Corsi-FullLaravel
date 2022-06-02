<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function show($id)
    {
        $course = Course::all()->find($id);

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

    public function getCover($course_id)
    {
        $course = Course::all()->find($course_id);
        $images = $course->images->first();

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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->description = $request->description;
        $course->price = $request->price;

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

    public function update(Request $request, $id)
    {
        $course = Course::all()->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

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

    public function destroy($id)
    {
        $course = Course::all()->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        if ($course->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Course can not be deleted'
            ], 500);
        }
    }
}
