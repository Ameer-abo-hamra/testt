<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * إنشاء Job مع البيانات المطلوبة.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * تنفيذ Job رفع الفيديو.
     */
    public function handle()
    {
        try {
            // إنشاء الفيديو في قاعدة البيانات
            $video = Video::create([
                'disk' => 'videos_disk',
                'original_name' => $this->request->file->getClientOriginalName(),
                'path' => fileupload($this->request, "videos_disk", "video"),
                'title' => $this->request->title,
            ]);

            // إرسال مهام تحويل الفيديو
            dispatch(new ConvertVideoForDownloading($video));
            dispatch(new ConvertVideoForStreaming($video));
        } catch (\Exception $e) {
            \Log::error('Error processing video upload: ' . $e->getMessage());
        }
    }
}
