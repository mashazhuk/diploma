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
        $lessonsByDate = $lessons->groupBy(function ($item) {
            return Carbon::parse($item->start_time)->format('Y-m-d');
        })->map(function ($lessonsOnSameDate) {
            return $lessonsOnSameDate->sortBy(function ($lesson) {
                return Carbon::parse($lesson->start_time)->format('H:i:s');
            }) -> values();
        });

        return response()->json($lessonsByDate);
    }
}
