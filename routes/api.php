<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\MemberController;
use \App\Http\Controllers\CardDetailController;
use \App\Http\Controllers\InsuranceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resource('members', MemberController::class);
Route::resource('cardDetails', CardDetailController::class);
Route::resource('insurances', InsuranceController::class);
