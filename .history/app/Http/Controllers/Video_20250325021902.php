<?php

namespace App\Http\Controllers;

use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as LaravelFFMpeg;
class Video extends Controller
{
    public function convertToHLS()
    {
        // المسار اليدوي للفيديو الأصلي
        $lowBitrate = (new X264)->setKiloBitrate(250);
        $midBitrate = (new X264)->setKiloBitrate(500);
        $highBitrate = (new X264)->setKiloBitrate(1000);

        \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('videos')
            ->open('steve_howe.mp4')
            ->exportForHLS()
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save('adaptive_steve.m3u8');

        return response()->json([
            'message' => 'تم التحويل بنجاح',
            'path' => asset($destinationPath . "/index.m3u8")
        ]);
    }

}
