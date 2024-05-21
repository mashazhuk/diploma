<?php

namespace App\Http\Controllers;
use App\Models\GroupLesson;

use Illuminate\Http\Request;

class GroupLessonController extends Controller
{
    public function getGroupName($id) {
        $group = GroupLesson::where('lesson_id', $id)->pluck('group_name');
        return response()->json($group);
    }

    public function updateGroups(Request $request, $id) {
        $newGroups = $request->get('groups');
        GroupLesson::where('lesson_id', $id)->delete();
        foreach ($newGroups as $groupName) {
            GroupLesson::create([
                'lesson_id' => $id,
                'group_name' => $groupName,
            ]);
        }
        return response()->json(['status' => 'success']);
    }
}

