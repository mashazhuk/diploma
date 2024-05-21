<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupLessonController;
use App\Http\Controllers\GroupController;

//Open routes
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);
Route::post("update/{id}", [LessonController::class, 'update']);
Route::post("add-lesson", [LessonController::class, 'addLesson']);
Route::post("delete-lesson/{id}", [LessonController::class, 'deleteLesson']);
Route::get("get-group-name/{id}", [GroupLessonController::class, 'getGroupName']);
Route::get("get-groups", [GroupController::class, 'getGroups']);
Route::post("update-groups/{id}", [GroupLessonController::class, 'updateGroups']);
Route::get("get-teachers", [UserController::class, 'getTeachers']);

//Protected routes 
Route::group([
    "middleware" => ["auth:sanctum"]
], function() {
    Route::get("sorted-lessons", [LessonController::class, 'getSortedLessons']);
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