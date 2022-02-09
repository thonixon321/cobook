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
Route::middleware('auth:sanctum')->put('/user/latLng', [UserController::class, 'updateUserLatLng']);
Route::middleware('auth:sanctum')->delete('/user/{userId}', [UserController::class, 'deleteUser']);

//Workshop Routes
//*note* the index route can also accept query for hostName to get workshops by host name
Route::middleware('auth:sanctum')->get('/workshops', [WorkshopController::class, 'index']);
Route::middleware('auth:sanctum')->post('/workshops', [WorkshopController::class, 'create']);
Route::middleware('auth:sanctum')->put('/workshops/{workshopId}/location/{locationId}', [WorkshopController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/workshops/{workshopId}', [WorkshopController::class, 'delete']);
Route::middleware('auth:sanctum')->post('/workshops/{workshopId}/addAttendee', [WorkshopController::class, 'addAttendee']);
Route::middleware('auth:sanctum')->get('/workshops/{workshopId}/attendees', [WorkshopController::class, 'getAttendees']);
Route::middleware('auth:sanctum')->delete('/workshops/{workshopId}/attendees', [WorkshopController::class, 'removeAttendee']);

//Alert User Routes

//Admin Routes
Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);