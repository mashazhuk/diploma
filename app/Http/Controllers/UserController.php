<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getRole()
    {
        $id = Auth::user()->id;
        $currentuser = User::find($id);
        $role = $currentuser->role;
        return response()->json(['role' => $role]);
    }

    public function getTeachers() {
        $teachers = User::where('role', 'admin')
                        ->select('id', 'last_name', 'name', 'surname')
                        ->get()
                        ->toArray();
        
        $teachersArray = [];
        foreach ($teachers as $teacher) {
            $id = $teacher['id'];
            $fullName = $teacher['surname'] . ' ' . $teacher['name'] . ' ' . $teacher['last_name'];
            $teachersArray[] = [
                'id' => (int)$id,
                'name' => $fullName
            ];
        }
    
        return response()->json($teachersArray);
    }

    public function getCurrentTeacher($id) {
        $currentTeacher = User::select('last_name', 'name', 'surname')->find($id);

        $fullName = $currentTeacher['surname'] . ' ' . $currentTeacher['name'] . ' ' . $currentTeacher['last_name'];
        $teacher = [
            'id' => (int)$id,
            'name' => $fullName
        ];

        return response()->json($teacher);
    }

    public function getUserGroupName() {
        $id = Auth::user()->id;
        $currentuser = User::find($id);
        $group = $currentuser->group_name;
        return response()->json($group);
    }
    
    

    // public function getTeachers() {
    //     $teachers = User::where('role', 'admin')->select('female', 'name', 'surname')->get();
    //     return response()->json($teachers);
    // }

    // public function editProfile(Request $request)
    // {
    //     $id = Auth::user()->id;
    //     $user = User::find($id);
    //     $user->update($request->all());
    //     return response()->json($user, 200);
    // }
}
