<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;
class VideoController extends Controller
{
    public function store(Request $request)
    {
        try {
            dispatch(new ProcessVideoUpload($request));
            return "Your video upload is processing :)";
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
