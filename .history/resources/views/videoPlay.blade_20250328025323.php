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

        var player = videojs('videoPlayer', {
            responsive: true,
            fluid: true,
            playbackRates: [0.5, 1, 1.5, 2], // سرعات التشغيل
        });

        // تفعيل HLS.js إذا كان المتصفح يدعمه
        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.autoLevelEnabled = false; // تعطيل الاختيار التلقائي

            hls.loadSource("{{ asset('streamable_videos/' . $masterFile) }}");
            hls.attachMedia(player.el());

            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                const qualitySelector = document.getElementById('qualitySelector');

                // مسح أي خيارات سابقة
                qualitySelector.innerHTML = '';

                // إضافة خيار "تلقائي"
                let autoOption = document.createElement('option');
                autoOption.value = 'auto';
                autoOption.text = 'Auto';
                qualitySelector.appendChild(autoOption);

                // إضافة باقي الجودات المتاحة
                hls.levels.forEach((level, index) => {
                    let option = document.createElement('option');
                    option.value = index;
                    option.text = `${level.height}p (${level.bitrate / 1000} kbps)`;
                    qualitySelector.appendChild(option);
                });

                // تعيين الاستجابة لاختيار الجودة
                qualitySelector.addEventListener('change', function() {
                    if (this.value === 'auto') {
                        hls.currentLevel = -1; // السماح بالتبديل التلقائي
                        hls.autoLevelEnabled = true;
                    } else {
                        hls.currentLevel = parseInt(this.value);
                        hls.autoLevelEnabled = false; // تعطيل التغيير التلقائي
                        hls.startLoad(); // إعادة تحميل الفيديو بالجودة الجديدة
                    }
                });
            });

        } else if (player.canPlayType('application/vnd.apple.mpegurl')) {
            player.src({
                src: "{{ asset('streamable_videos/' . $masterFile) }}",
                type: "application/x-mpegURL"
            });
        }
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
            console.log("الجودات المتاحة:", hls.levels);
        });

    </script>

</body>

</html>
