<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkshopController;


//User Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/latLng/{address}', [UserController::class, 'getLatLng']);
//dev purposes only
Route::get('/users', [UserController::class, 'index']);

//Workshop Routes
Route::middleware('auth:sanctum')->get('/workshops', [WorkshopController::class, 'index']);
Route::middleware('auth:sanctum')->post('/workshops', [WorkshopController::class, 'create']);
