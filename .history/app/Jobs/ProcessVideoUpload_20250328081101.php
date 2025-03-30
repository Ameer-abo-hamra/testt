<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoPath;
    protected $fileName;

    /**
     * إنشاء كائن Job مع بيانات الفيديو.
     */
    public function __construct($videoPath, $fileName)
    {
        $this->videoPath = $videoPath;
        $this->fileName = $fileName;
    }

    /**
     * تنفيذ Job معالجة الفيديو.
     */
    public function handle()
    {
        // نقل الفيديو من المجلد المؤقت إلى التخزين الدائم
        $storagePath = 'public/videos/' . $this->fileName;
        Storage::move($this->videoPath, $storagePath);

        // يمكن هنا تنفيذ أي عمليات أخرى مثل التحويل إلى HLS
    }
}
