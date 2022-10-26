<?php

use App\Http\Controllers\ResedentialController;
use Illuminate\Support\Facades\Route;

// All Endpoint API For Residential //

// Get All Residential Data
Route::get('get-resedentials',[ResedentialController::class,'index']);

// Store or Add Residential Data
Route::post('add-resedential',[ResedentialController::class,'store']);

// Get One Residential Data By Id
Route::get('get-resedential/{id}',[ResedentialController::class,'show']);

// Update Residential Data By Id
Route::put('update-resedential/{id}',[ResedentialController::class,'update']);

// Delete Residential Data By Id
Route::delete('delete-resedential/{id}',[ResedentialController::class,'destroy']);

// If Access Another Endpoint API
Route::any('{path}', function() {
    return response()->json([
        'code' => 404,
        'message' => 'Api not found'
    ], 404);
})->where('path', '.*');
