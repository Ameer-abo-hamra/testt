<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script> <!-- مكتبة HLS.js -->
</head>
<body>

    <h2>مشغل الفيديو مع اختيار الجودة</h2>

    <video id="videoPlayer" controls></video>

    <select id="qualitySelector">
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
