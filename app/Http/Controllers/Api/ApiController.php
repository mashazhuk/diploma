<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request) {

        //Validation
        $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed"
        ]);

        //User
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" =>bcrypt($request->password)
        ]);

        $token = $user->createToken("mytoken")->plainTextToken;

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "token" => $token,
            "data" => []
        ]);

    }

    public function login(Request $request) {
        //Validate
        $request -> validate([
            "email" => "required|email|string",
            "password" => "required|"
        ]);

        $user = User::where("email", $request->email)->first();

        if(!empty($user)) {
            //User exist
            if(Hash::check($request->password, $user->password)) {
                //Password matched
                $token = $user->createToken("mytoken")->plainTextToken;


                return response()->json([
                    "status" => true,
                    "message" => "User logged in",
                    "token" => $token,
                    "data" => []
                ]);

            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Invalid password",
                    "data" => []
                ], 422);
            }

        } else {
            return response()->json([
                "status" => false,
                "message" => "Please pass email and password",
                "data" => []
            ], 401);
        };
    }

    public function profile() {
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            "message"=>"Profile information",
            "data" => $userData 
        ]);
    }

    public function editProfile(Request $request) {
        $user = auth()->user();
        $user->update($request->all());

        return response()->json([
            "status" => true,
            "message"=>"Edit profiiile",
            "data" => $user
        ]);
    }

    // public function getUserGroupName($id) {
    //     $user = auth()->user();
    //     $currentuser = User::find($id);
    //     $group = $currentuser->group_name;
    //     return response()->json($group);
    // }

    public function logout() {
        $userData = auth()->user();
        // Auth::user()->tokens()->delete();
        // auth()->user()->tokens()->delete();
        
        return response()->json([
            "status" => true,
            "message" => "User logged out",
            "data" => $userData
        ]);
    //     $request->user()->tokens()->delete();

    // $request->session()->invalidate();

    // $request->session()->regenerateToken();

    // return response()->json(['message' => 'User logged out successfully']);
    }
}
