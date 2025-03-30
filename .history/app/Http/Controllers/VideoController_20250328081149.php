<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVideoUpload;
use Illuminate\Http\Request;

use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;
class VideoController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // الحد الأقصى 100MB
        ]);

        // رفع الفيديو إلى مجلد مؤقت
        $videoPath = $request->file('video')->store('temp_videos');

        // اسم الملف الفعلي
        $fileName = $request->file('video')->getClientOriginalName();

        // إرسال المهمة إلى الـ Queue
        ProcessVideoUpload::dispatch($videoPath, $fileName);
        try {
            $video = \App\Models\Video::create([
                'disk' => 'videos_disk',
                'original_name' => $request->file->getClientOriginalName(),
                'path' => fileupload($request, "videos_disk", "video"),
                'title' => $request->title,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        dispatch(new ConvertVideoForDownloading($video));
        dispatch(new ConvertVideoForStreaming($video));

        return "your video uploads is processing :)";
    }
}
