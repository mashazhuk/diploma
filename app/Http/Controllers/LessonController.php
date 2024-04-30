<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Http\Resources\LessonResource;
use Carbon\Carbon;

class LessonController extends Controller
{
    public function index(Request $request) {
        return LessonResource::collection(Lesson::all());
    }

    public function getSortedLessons() {
        $lessons = Lesson::all();
        $lessonsByDate = $lessons->groupBy('lesson_date')->map(function ($lessonsOnSameDate) {
            return $lessonsOnSameDate->sortBy('start_time')->values();
        });

        return response()->json($lessonsByDate);
    }

    public function update(Request $request, $id) {
        $lesson = Lesson::find($id);
        $lesson->update($request->all());
        return response()->json($lesson, 200);
    }

}
