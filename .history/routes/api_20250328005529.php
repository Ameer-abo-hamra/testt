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

    // البحث عن الملف الرئيسي master.m3u8 المتعلق بـ video_id
    $masterFile = null;

    // البحث في الملفات الموجودة عن الملف الرئيسي للمقطع
    $files = File::files($directory);
    foreach ($files as $file) {
        if (str_contains($file->getFilename(), $video_id) && $file->getFilename() === "master.m3u8") {
            $masterFile = $file->getFilename();
            break;
        }
    }

    if (!$masterFile) {
        abort(403, "Video not found");
    }

    return view("videoPlay", compact("masterFile", "video_id"));
});

