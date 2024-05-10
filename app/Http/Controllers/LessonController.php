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

    public function addLesson(Request $request) {
        Lesson::create([
            "lesson_name" => $request->lesson_name,
            "lesson_date" => $request->lesson_date,
            "start_time" => $request->start_time,
            "end_time" => $request->end_time,
            "conference_id" => $request->conference_id,
            "conference_password" => $request->conference_password,
            "type_of_week" => $request->type_of_week
        ]);

        return response()->json('Урок успішно додано!');
    }

    public function deleteLesson(Request $request, $id) {
        $lesson = Lesson::find($id);
        $lesson->delete();
        return response()->json('Урок успішно видалено!');
    }

}
