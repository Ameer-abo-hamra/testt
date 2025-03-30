<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشغل الفيديو</title>

    <!-- استدعاء مكتبة Video.js -->
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.js"></script>
    <script src="https://unpkg.com/videojs-http-streaming@2.10.0/dist/videojs-http-streaming.min.js"></script>

    <!-- مكتبة اختيار الجودة -->
    <script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector"></script>

    <style>
        /* ضبط الصفحة ليكون الفيديو في المنتصف */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #111;
            color: white;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        /* حاوية الفيديو */
        .video-container {
            width: 90%;
            max-width: 800px;
            aspect-ratio: 16 / 9;
        }

        /* تصميم الفيديو */
        .video-js {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>

    <h2>مشغل الفيديو مع اختيار الجودة</h2>

    <!-- مشغل الفيديو -->
    <div class="video-container">
        <video id="videoPlayer" class="video-js vjs-default-skin" controls>
            <source src="{{ asset('streamable_videos/' . $masterFile) }}" type="application/x-mpegURL">
        </video>
    </div>

    <script>
     var player = videojs('videoPlayer', {
    responsive: true,
    fluid: true,
    playbackRates: [0.5, 1, 1.5, 2], // سرعات التشغيل
    sources: [{
        src: "{{ asset('streamable_videos/' . $masterFile) }}",
        type: "application/x-mpegURL"
    }],
    plugins: {
        httpStreaming: {} // تأكد من إضافة هذا لتفعيل دعم HLS
    }
});

// تفعيل مكتبة اختيار الجودة
player.hlsQualitySelector({
    displayCurrentQuality: true, // عرض الجودة الحالية في زر الإعدادات
    defaultQuality: "auto" // لتحديد الجودة الافتراضية إلى "آلي"
});

player.ready(function () {
    this.hlsQualitySelector(); // تأكد من تشغيل الاختيار عند تحميل الفيديو
});

    </script>

</body>

</html>
