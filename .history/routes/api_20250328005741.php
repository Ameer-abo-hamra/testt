<?php

use App\Http\Controllers\Video;
use App\Http\Controllers\VideoController;
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


Route::get('/video-upload', function () {
    return view('upload_video');
});

Route::post("upload-video", [VideoController::class, "store"])->name("upload.video");

// Route::get("video/{video_id}", function ($video_id) {

//     return view("videoPlay", compact("video_id"));
// });

// use Illuminate\Support\Facades\File;

Route::get("video/{video_id}", function ($video_id) {
    $directory = public_path("streamable_videos");

    // البحث عن الملف الرئيسي الذي يحمل اسم video_id.m3u8
    $masterFile = null;

    // البحث في الملفات الموجودة عن الملف الذي يحتوي على video_id في اسمه
    $files = File::files($directory);
    foreach ($files as $file) {
        if (str_contains($file->getFilename(), $video_id) && $file->getExtension() === 'm3u8') {
            $masterFile = $file->getFilename();
            break;
        }
    }

    if (!$masterFile) {
        abort(404, "Video not found");
    }

    return view("videoPlay", compact("masterFile", "video_id"));
});


