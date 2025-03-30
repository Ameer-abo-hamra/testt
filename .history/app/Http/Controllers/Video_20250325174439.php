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
            ->open('x.mp4')
            ->exportForHLS()  // تصدير الفيديو بتنسيق HLS
            ->setSegmentLength(10)  // تعيين طول المقطع إلى 10 ثواني
            ->setKeyFrameInterval(48)  // تعيين فاصل الإطار الرئيسي إلى 48
            ->addFormat($lowBitrate)  // إضافة تنسيق منخفض البت
            ->addFormat($midBitrate)  // إضافة تنسيق متوسط البت
            ->addFormat($highBitrate)  // إضافة تنسيق مرتفع البت
            ->toDisk("converted_video")
            ->save('adaptive_video.m3u8');  // حفظ الفيديو بتنسيق M3U8

        return response()->json([
            'message' => 'تم التحويل بنجاح',
            // 'path' => asset($destinationPath . "/index.m3u8")
        ]);
    }

    public function listVideos()
    {
        $videos = array_diff(scandir(public_path('video')), ['.', '..']);

        return view('videos', compact('videos'));
    }

    public function playVideo($video)
    {
        return view('play', compact('video'));
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:500000000', // التحقق من امتداد الفيديو
        ]);

        // حفظ الفيديو الأصلي في مجلد "public/video"
        $video = $request->file('video');

        $videoName = time() . '.' . $video->getClientOriginalExtension();
        $video->move(public_path('Video'), $videoName);
        // مسار الفيديو الأصلي
        $originalPath = public_path("Video/{$videoName}");

        // مجلد التخزين بعد التحويل إلى HLS
        $convertedPath = public_path("converted_video/{$videoName}_hls");

        if (!file_exists($convertedPath)) {
            mkdir($convertedPath, 0777, true);
        }

        // تحديد إعدادات الجودة المختلفة
        $low = (new X264)->setKiloBitrate(250);
        $mid = (new X264)->setKiloBitrate(500);
        $high = (new X264)->setKiloBitrate(1000);

        // تحويل الفيديو إلى HLS
        FFMpeg::fromDisk('public')
            ->open("Video/{$videoName}")
            ->exportForHLS()
            ->addFormat($low)
            ->addFormat($mid)
            ->addFormat($high)
            ->toDisk("converted_video")
            ->save("converted_video/{$videoName}_hls");

        return response()->json([
            'message' => 'تم رفع وتحويل الفيديو بنجاح!',
            'video_name' => $videoName,
            'converted_path' => asset("converted_video/{$videoName}_hls/master.m3u8"),
        ]);
    }


}
