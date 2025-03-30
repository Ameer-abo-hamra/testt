<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $timeout = 1200; // زيادة المهلة إلى 20 دقيقة لتناسب المعالجة الطويلة

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        // تعريف الجودات المختلفة مع معدلات البت المناسبة
        $formats = [
            '144p' => (new X264)->setKiloBitrate(150),  // 144p
            '240p' => (new X264)->setKiloBitrate(400),  // 240p
            '360p' => (new X264)->setKiloBitrate(800),  // 360p
            '480p' => (new X264)->setKiloBitrate(1200), // 480p
            '720p' => (new X264)->setKiloBitrate(2500), // 720p
            '1080p' => (new X264)->setKiloBitrate(5000) // 1080p
        ];

        // فتح الفيديو المصدر من القرص المحدد
        $video = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)

            // تصدير للفيديوهات بصيغة HLS مع تخزينه في مجلد `streamable_videos`
            ->exportForHLS()
            ->toDisk('streamable_videos');

        // إضافة كل جودة إلى الفيديو النهائي
        foreach ($formats as $resolution => $format) {
            $video->addFormat($format);
        }

        // حفظ قائمة التشغيل الرئيسية باسم الفيديو
        $video->save($this->video->id . '.m3u8');

        // تحديث حالة الفيديو في قاعدة البيانات
        $this->video->update([
            'converted_for_streaming_at' => Carbon::now(),
        ]);
    }
}
