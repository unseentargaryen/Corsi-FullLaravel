<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $course_id = $request->course_id;
        $max_participants = $request->max_participants;
        $dates = $request->dates;
        $startTime = $request->startTime;
        $endTime = $request->endTime;
        $sede = $request->sede;

        Log::info($dates);
        if ($dates === '') {
            return response()->json(
                ['status' => 500]
            );
        }

        $dates = explode(",", $dates);

        try {
            foreach ($dates as $d) {
                $lesson = new Lesson();
                $lesson->course_id = $course_id;
                $lesson->max_participants = $max_participants;
                $lesson->seats_available = $max_participants;
                $lesson->start = Carbon::create(trim($d) . " " . $startTime);
                $lesson->end = Carbon::create(trim($d) . " " . $endTime);
                $lesson->sede = $sede;

                $lesson->save();
            }
        } catch (Exception $e) {
            return response()->json(
                ['status' => 500, 'message' => $e->getMessage()]
            );
        }

        return response()->json(
            ['status' => 200]
        );
    }

    public function edit(Request $request)
    {

        $lesson = Lesson::findOrFail($request->id);

        try {
            $lesson->max_participants = $request->max_participants;
            $lesson->start = Carbon::create($request->start);
            $lesson->end = Carbon::create($request->end);
            $lesson->sede = $request->sede;
            $lesson->visible = $request->visible;

            $lesson->save();
        } catch (Exception $e) {
            return response()->json(
                ['status' => 500, 'message' => $e->getMessage()]
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

        $lessons = Lesson::where('course_id', $course_id)->whereDate('start', '>=', Carbon::create($start))->whereDate('start', '>=', Carbon::tomorrow())->get();
        $_lessons = [];
        foreach ($lessons as $l) {
            $l->seats_available = ($l->max_participants - ($l->bookings()->count() + $l->pendingBookings()->count()));
            $l->bookings = $l->bookings()->count();
            $l->pendingBookings = $l->pendingBookings()->count();
            foreach ($l->pendingBookings()->get() as $_pendingBooking) {
                if ($_pendingBooking->user()->first()->id === Auth::user()->id) {
                    $l->hasPending = true;
                    $l->classNames = ['fc-event-pending'];
                }
            }
            if ($l->bookings < 4 || $l->hasPending) {
                $_lessons[] = $l;
            }
        }

        return response()->json(
            $_lessons
        );
    }

}
