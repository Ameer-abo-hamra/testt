<?php

use App\Http\Controllers\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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

Route::get("get-video" , function () {
    return View("home");
});


Route::get("convert_video/{path}" , [Videoo::class , "convertToHLS"] );
