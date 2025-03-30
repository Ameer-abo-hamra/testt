<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشغل الفيديو</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script> <!-- مكتبة HLS.js -->
    <style>
        /* ضبط الصفحة ليكون الفيديو في المنتصف */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #111; /* لون داكن للخلفية */
            color: white;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        /* حاوية الفيديو */
        .video-container {
            width: 90%;
            max-width: 800px; /* أقصى عرض للفيديو */
            aspect-ratio: 16 / 9; /* يجعل الفيديو متجاوبًا بنسبة 16:9 */
            position: relative;
        }

        /* عنصر الفيديو */
        video {
            width: 100%;
            height: 100%;
            border-radius: 10px; /* جعل الزوايا مستديرة */
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2); /* ظل خفيف للفيديو */
        }

        /* قائمة اختيار الجودة */
        .quality-selector {
            margin-top: 15px;
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #222;
            color: white;
            border: 1px solid #444;
        }
    </style>
</head>
<body>

    <h2>مشغل الفيديو مع اختيار الجودة</h2>

    <!-- حاوية الفيديو -->
    <div class="video-container">
        <video id="videoPlayer" controls></video>
    </div>

    <!-- قائمة اختيار الجودة -->
    <select id="qualitySelector" class="quality-selector">
        <option value="auto" selected>تلقائي</option>
    </select>

    <script>
        const video = document.getElementById("videoPlayer");
        const qualitySelector = document.getElementById("qualitySelector");

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource("{{ asset('streamable_videos/' . $video_id . '.m3u8') }}");
            hls.attachMedia(video);

            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                let levels = hls.levels;

                levels.forEach((level, index) => {
                    let quality = level.height + "p"; // استخراج دقة الفيديو
                    let option = new Option(quality, index);
                    qualitySelector.add(option);
                });

                qualitySelector.addEventListener("change", function () {
                    if (this.value === "auto") {
                        hls.currentLevel = -1; // تعيين الوضع التلقائي
                    } else {
                        hls.currentLevel = parseInt(this.value); // تحديد الجودة يدويًا
                    }
                });
            });
        } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
            video.src = "{{ asset('streamable_videos/' . $video_id . '.m3u8') }}";
        }
    </script>

</body>
</html>
