<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Http\Resources\LessonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LessonController extends Controller
{
    public function index(Request $request) {
        return LessonResource::collection(Lesson::all());
    }

    public function getSortedLessons() {
        // $lessons = Lesson::all();
        $id = Auth::user()->id;
        $user = User::find($id);

        $lessons = Lesson::where(function ($query) use ($user) {
            if ($user->role == 'admin') {
                $query->where('teacher', $user->id);
            } elseif ($user->role == 'student') {
            $query->whereHas('groupLesson', function ($query) use ($user) {
                $query->where('group_name', $user->group_name);
            });
        }
        })->get();

        $lessonsByDate = $lessons->groupBy(function ($lesson) {
            if ($lesson->type_of_week && $lesson->weekday) {
                return $lesson->type_of_week . '-' . $lesson->weekday;
            } else {
                // Если нет type_of_week и weekday, используйте lesson_date для группировки
                return $lesson->lesson_date;
            }
        })->map(function ($lessonsOnSameDay) {
            return $lessonsOnSameDay->sortBy('start_time')->values();
        });

        return response()->json($lessonsByDate);
    }

    public function update(Request $request, $id) {
        $lesson = Lesson::find($id);
        $lesson->update($request->all());
        return response()->json($lesson, 200);
    }

    public function addLesson(Request $request) {
        $lesson = Lesson::create($request->all());
        return response()->json($lesson, 200);
    }

    public function deleteLesson(Request $request, $id) {
        $lesson = Lesson::find($id);
        $lesson->delete();
        return response()->json('Урок успішно видалено!');
    }

}
