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

    <style>
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

        .video-container {
            width: 90%;
            max-width: 800px;
            aspect-ratio: 16 / 9;
        }

        .video-js {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        #error-message {
            margin-top: 20px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>مشغل الفيديو مع اختيار الجودة</h2>

    <!-- مشغل الفيديو -->
    <div class="video-container">
        <video id="videoPlayer" class="video-js vjs-default-skin" controls></video>
    </div>

    <select id="qualitySelector" style="margin-top: 10px;"></select>
    <div id="error-message"></div> <!-- عرض الأخطاء هنا -->

    <script>
        console.log("تم بدء تحميل الصفحة...");

        var video = document.getElementById('videoPlayer');
        var qualitySelector = document.getElementById('qualitySelector');
        var errorMessage = document.getElementById('error-message');

        var videoUrl = "{{ url('streamable_videos/' . $masterFile) }}";
        console.log("رابط الفيديو:", videoUrl);

        if (!videoUrl || videoUrl == "{{ url('streamable_videos/' . $masterFile) }}") {
            console.error("⚠️ خطأ: لم يتم تمرير رابط الفيديو بشكل صحيح!");
            errorMessage.innerText = "⚠️ خطأ: لم يتم تمرير رابط الفيديو بشكل صحيح!";
        } else if (Hls.isSupported()) {
            console.log("✅ HLS مدعوم، سيتم تحميل الفيديو...");

            var hls = new Hls();

            hls.loadSource(videoUrl);
            hls.attachMedia(video);

            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                console.log("🎥 تم تحليل ملف m3u8 بنجاح!");

                qualitySelector.innerHTML = '';
                let autoOption = document.createElement('option');
                autoOption.value = 'auto';
                autoOption.text = 'Auto';
                qualitySelector.appendChild(autoOption);

                hls.levels.forEach((level, index) => {
                    let option = document.createElement('option');
                    option.value = index;
                    option.text = `${level.height}p (${(level.bitrate / 1000).toFixed(0)} kbps)`;
                    qualitySelector.appendChild(option);
                });

                qualitySelector.addEventListener('change', function() {
                    hls.currentLevel = this.value === 'auto' ? -1 : parseInt(this.value);
                });

                console.log("✅ الجودات المتاحة:", hls.levels);
            });

            hls.on(Hls.Events.ERROR, function(event, data) {
                console.error("❌ خطأ في تحميل الفيديو:", data);
                errorMessage.innerText = "❌ خطأ: " + data.details;
            });

        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            console.log("🔄 سيتم تحميل الفيديو باستخدام تنسيق HLS الأصلي...");
            video.src = videoUrl;
        } else {
            console.error("🚫 متصفحك لا يدعم HLS!");
            errorMessage.innerText = "🚫 متصفحك لا يدعم تشغيل HLS!";
        }
    </script>

</body>

</html>
