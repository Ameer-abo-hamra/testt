<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشغل الفيديو</title>

    <!-- استدعاء مكتبة Video.js -->
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.js"></script>

    <!-- استدعاء مكتبة HLS.js -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <!-- استدعاء مكتبة videojs-hls-quality-selector -->
    <script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@latest"></script>

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
        console.log("hi");
        var video = document.getElementById('videoPlayer');
        var qualitySelector = document.createElement("select");
        qualitySelector.id = "qualitySelector";
        document.body.appendChild(qualitySelector); // إضافته إلى الصفحة

        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource("{{ asset('streamable_videos/' . $masterFile) }}");
            hls.attachMedia(video);

            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                console.log("الجودات المتاحة:", hls.levels);

                qualitySelector.innerHTML = ''; // تفريغ القائمة قبل الإضافة

                // إضافة خيار "تلقائي"
                let autoOption = document.createElement('option');
                autoOption.value = 'auto';
                autoOption.text = 'Auto';
                qualitySelector.appendChild(autoOption);

                // إضافة الجودات المتاحة
                hls.levels.forEach((level, index) => {
                    let option = document.createElement('option');
                    option.value = index;
                    option.text = `${level.height}p (${(level.bitrate / 1000).toFixed(0)} kbps)`;
                    qualitySelector.appendChild(option);
                });

                // عند تغيير الجودة
                qualitySelector.addEventListener('change', function() {
                    if (this.value === 'auto') {
                        hls.currentLevel = -1;
                    } else {
                        hls.currentLevel = parseInt(this.value);
                    }
                });

                console.log("تم تحميل الجودات بنجاح.");
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {

            console.log("else if ")
            video.src = "{{ asset('streamable_videos/' . $masterFile) }}";
        }
    </script>

</body>

</html>
