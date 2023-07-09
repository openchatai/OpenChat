<?php

use App\Http\Api\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('chat/search', [MessageController::class, 'sendSearchRequest']);


Route::get('chat/init', [MessageController::class, 'initChat']);
Route::post('chat/send', [MessageController::class, 'sendChat']);
