<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as LaravelFFMpeg;
class Video extends Controller
{
    public function convertToHLS()
    {
        // المسار اليدوي للفيديو الأصلي
        $videoPath = public_path('Video/x.mp4');

        // اسم الملف بدون الامتداد
        $fileName = pathinfo($videoPath, PATHINFO_FILENAME);

        // مجلد الوجهة داخل public/converted_video
        $destinationPath = 'converted_video/' . $fileName;

        LaravelFFMpeg::open($videoPath) // استخدم المسار اليدوي
            ->exportForHLS()
            ->addFormat(LaravelFFMpeg::makeFormat('ts'))
            ->save(public_path($destinationPath . '/index.m3u8'));

        return response()->json([
            'message' => 'تم التحويل بنجاح',
            'path' => asset($destinationPath . "/index.m3u8")
        ]);
    }

}
