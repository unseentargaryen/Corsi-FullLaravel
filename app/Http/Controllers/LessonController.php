<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function all()
    {

        $lessons = Lesson::all();

        return response()->json(
            $lessons
        );
    }

    public function create(Request $request)
    {

        Log::info($request);

        $course_id = $request->course_id;
        $max_participants = $request->max_participants;
        $dates = $request->dates;
        $startTime = $request->startTime;
        $endTime = $request->endTime;

        $dates = explode(",", $dates);
        try {
            foreach ($dates as $d) {
                $lesson = new Lesson();
                $lesson->course_id = $course_id;
                $lesson->max_participants = $max_participants;
                $lesson->seats_available = $max_participants;
                $lesson->start = Carbon::createFromDate(trim($d) . " " . $startTime);
                $lesson->end = Carbon::createFromDate(trim($d) . " " . $endTime);

                $lesson->save();
            }
        } catch (Exception $e) {
            return response()->json(
                ['status' => 500]
            );
        }

        return response()->json(
            ['status' => 200]
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