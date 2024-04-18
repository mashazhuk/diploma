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
}
