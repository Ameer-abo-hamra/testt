<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as LaravelFFMpeg;
class Video extends Controller
{
    public function convertToHLS($videoPath)
    {
        $destinationPath = 'public/hls/' . pathinfo($videoPath, PATHINFO_FILENAME);

        LaravelFFMpeg::fromDisk('videos')
            ->open($videoPath)
            ->exportForHLS()
            ->addFormat(LaravelFFMpeg::makeFormat('ts'))
            ->save($destinationPath . '/index.m3u8');

        return response()->json(['message' => 'تم التحويل بنجاح', 'path' => asset("storage/hls/" . pathinfo($videoPath, PATHINFO_FILENAME) . "/index.m3u8")]);
    }
}
