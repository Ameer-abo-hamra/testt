<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاهدة الفيديو</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        /* تنسيق الصفحة */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        /* تنسيق الفيديو */
        .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        video {
            width: 80%;
            max-width: 900px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #000;
        }
    </style>
</head>

<body>
    <h2>تشغيل الفيديو</h2>
    <div class="video-container">
        <video id="video" controls></video>
    </div>

    <script>
        var video = document.getElementById('video');
        var videoSource = "{{ asset('converted_video/converted_video/1742914347' . '.mp4_hls/master_1_500.m3u8') }}";

        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(videoSource);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = videoSource;
            video.play();
        }
    </script>
</body>

</html>
