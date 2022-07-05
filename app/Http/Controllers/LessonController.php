<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function all()
    {

        $lessons = Lesson::all();

        return response()->json(
            $lessons
        );
    }

    public function getLessonsByCourse(Request $request, $course_id)
    {

        $start = $request->start;
        $end = $request->end;

        $lessons = Lesson::where('course_id', $course_id)->whereDate('start', '>=', $start)->whereDate('end', '<=', $end)->get();

        return response()->json(
            $lessons
        );
    }
}
