<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;

//Open routes
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);
Route::get("sorted-lessons", [LessonController::class, 'getSortedLessons']);
Route::post("update/{id}", [LessonController::class, 'update']);


//Protected routes 
Route::group([
    "middleware" => ["auth:sanctum"]
], function() {
    Route::get('profile', [ApiController::class, "profile"]);
    Route::get('logout', [ApiController::class, "logout"]);
    Route::get("get-role", [UserController::class, 'getRole']);
    
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::resources([
//     'lessons' => LessonController::class,
// ]);

// Route::get('sorted-lessons', [LessonController::class, 'getSortedLessons']);