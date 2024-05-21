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
                        ->select('id', 'female', 'name', 'surname')
                        ->get()
                        ->toArray();
        
        $teachersArray = [];
        foreach ($teachers as $teacher) {
            $id = $teacher['id'];
            $fullName = $teacher['surname'] . ' ' . $teacher['name'] . ' ' . $teacher['female'];
            $teachersArray[] = [
                'id' => $id,
                'name' => $fullName
            ];
        }
    
        return response()->json($teachersArray);
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
