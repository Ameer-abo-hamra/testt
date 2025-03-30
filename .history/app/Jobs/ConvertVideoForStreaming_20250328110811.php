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
    public $timeout = 1200; // المهلة 20 دقيقة

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        // قائمة الدقات مع معدل البت والدقة المناسبة
        $formats = [
            ['bitrate' => 150,  'resolution' => '256x144'],   // 144p
            ['bitrate' => 400,  'resolution' => '426x240'],   // 240p
            ['bitrate' => 800,  'resolution' => '640x360'],   // 360p
            ['bitrate' => 1200, 'resolution' => '854x480'],   // 480p
            ['bitrate' => 2500, 'resolution' => '1280x720'],  // 720p
            ['bitrate' => 5000, 'resolution' => '1920x1080'], // 1080p
        ];

        // تحميل الفيديو الأصلي من القرص المحدد
        $video = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            ->exportForHLS()
            ->toDisk('streamable_videos');

        // تطبيق كل دقة على الفيديو
        foreach ($formats as $index => $format) {
            $video->addFormat(
                (new X264)->setKiloBitrate($format['bitrate']),
                function ($filters) use ($format) {
                    $filters->resize($format['resolution'])->framerate(30);
                }
            );
        }

        // حفظ قائمة التشغيل الرئيسية
        $video->save($this->video->id . '.m3u8');

        // تحديث قاعدة البيانات
        $this->video->update([
            'converted_for_streaming_at' => Carbon::now(),
        ]);
    }
}
