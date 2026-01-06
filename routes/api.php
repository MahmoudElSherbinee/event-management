<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventController::class)
        ->only(['index', 'show']);
Route::apiResource('events', EventController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware('auth:sanctum');


Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped()
        ->only(['store', 'destroy'])
        ->middleware('auth:sanctum');
Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped()
        ->only(['index', 'show']);


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('logout', [AuthController::class, 'logout'])
->middleware('auth:sanctum');


