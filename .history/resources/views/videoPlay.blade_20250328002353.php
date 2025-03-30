<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشغل الفيديو</title>
    <link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            flex-direction: column;
        }
        .video-container {
            max-width: 800px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="video-container">
        <h2>مشغل الفيديو</h2>
        <video id="video-player" class="video-js vjs-default-skin" controls preload="auto" width="720" height="400">
            <source src="{{asset(streamable_videos/{{ $video_id }}.m3u8)}}" type="application/x-mpegURL">
        </video>
    </div>
    <script src="https://vjs.zencdn.net/8.3.0/video.min.js"></script>
    <script>
        var player = videojs('video-player');
    </script>
</body>
</html>
