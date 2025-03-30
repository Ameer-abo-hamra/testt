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
            
        }

        // // dispatch(new ConvertVideoForDownloading($video));
        // // dispatch(new ConvertVideoForStreaming($video));

        return "your video uploads is processing :)";
    }
}
