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
Route::post("add-lesson", [LessonController::class, 'addLesson']);
Route::post("delete-lesson/{id}", [LessonController::class, 'deleteLesson']);


//Protected routes 
Route::group([
    "middleware" => ["auth:sanctum"]
], function() {
    Route::post('logout', [ApiController::class, "logout"]);
    Route::get('profile', [ApiController::class, "profile"]);
    Route::post('edit-profile', [ApiController::class, 'editProfile']);
    Route::get("get-role", [UserController::class, 'getRole']);
    
    
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::resources([
//     'lessons' => LessonController::class,
// ]);

// Route::get('sorted-lessons', [LessonController::class, 'getSortedLessons']);