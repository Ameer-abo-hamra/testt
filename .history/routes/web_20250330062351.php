<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
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

    $masterFile = null;
    $files = File::files($directory);
    foreach ($files as $file) {
        if (preg_match("/^" . preg_quote($video_id, '/') . "\.m3u8$/", $file->getFilename())) {
            $masterFile = $file->getFilename();
            break;
        }
    }

    if (!$masterFile) {
        abort(404, "Video not found");
    }

    return view("videoPlay", compact("masterFile", "video_id"));
});


Route::get('streamable_videos/{file}', function ($file) {
    $path = public_path("streamable_videos/{$file}.m3u8");

    if (!File::exists($path)) {
        abort(404, "File not found");
    }

    // تحديد Content-Type حسب امتداد الملف
    $extension = File::extension($path);
    $contentType = ($extension === 'm3u8') ? 'application/vnd.apple.mpegurl' : 'application/octet-stream';

    return response()->file($path, [
        'Content-Type' => $contentType
    ]);
})->name('video.stream');


Route::get('bb/{streamable_videos}', function ($streamable_videos) {
    return view("videoPlay", compact("streamable_videos"));
});
