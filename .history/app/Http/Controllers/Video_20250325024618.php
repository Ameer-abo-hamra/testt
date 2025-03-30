<?php

namespace App\Http\Controllers;

use FFMpeg\Format\Video\X264;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as LaravelFFMpeg;
class Video extends Controller
{
    public function convertToHLS()
    {
        $lowBitrate = (new X264)->setKiloBitrate(250);  // معدل بت منخفض (250 كيلوبت في الثانية)
        $midBitrate = (new X264)->setKiloBitrate(500);  // معدل بت متوسط (500 كيلوبت في الثانية)
        $highBitrate = (new X264)->setKiloBitrate(1000); // معدل بت مرتفع (1000 كيلوبت في الثانية)

        FFMpeg::fromDisk('video')  // افتح الفيديو من القرص
            ->open('.mp4')
            ->exportForHLS()  // تصدير الفيديو بتنسيق HLS
            ->setSegmentLength(10)  // تعيين طول المقطع إلى 10 ثواني
            ->setKeyFrameInterval(48)  // تعيين فاصل الإطار الرئيسي إلى 48
            ->addFormat($lowBitrate)  // إضافة تنسيق منخفض البت
            ->addFormat($midBitrate)  // إضافة تنسيق متوسط البت
            ->addFormat($highBitrate)  // إضافة تنسيق مرتفع البت
            ->save('adaptive_video.m3u8');  // حفظ الفيديو بتنسيق M3U8

        return response()->json([
            'message' => 'تم التحويل بنجاح',
            // 'path' => asset($destinationPath . "/index.m3u8")
        ]);
    }

}
