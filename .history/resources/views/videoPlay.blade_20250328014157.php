<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشغل الفيديو</title>
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>

<h2>مشغل الفيديو مع اختيار الجودة</h2>

<!-- مشغل الفيديو -->
<video id="videoPlayer" class="video-js vjs-default-skin" controls>
    <source src="path_to_video/11_0_500.m3u8" type="application/x-mpegURL">
</video>

<script>
    var player = videojs('videoPlayer', {
        responsive: true,
        fluid: true,
        playbackRates: [0.5, 1, 1.5, 2],
    });

    // تفعيل HLS.js إذا كان المتصفح يدعمه
    if (Hls.isSupported()) {
        var hls = new Hls();

        // تحميل مصدر HLS
        hls.loadSource("path_to_video/11_0_500.m3u8");

        hls.on(Hls.Events.MANIFEST_PARSED, function(event, data) {
            // إضافة الجودات المتاحة في قائمة
            var qualities = data.levels;
            var qualitySelector = document.createElement('select');
            qualities.forEach(function(quality, index) {
                var option = document.createElement('option');
                option.value = index;
                option.innerText = quality.height + 'p';
                qualitySelector.appendChild(option);
            });
            document.body.appendChild(qualitySelector);

            // تغيير الجودة عند اختيار المستخدم
            qualitySelector.addEventListener('change', function() {
                hls.startLevel = qualitySelector.value;
                hls.loadSource(qualities[qualitySelector.value].url);
            });
        });

        // ربط HLS.js مع مشغل الفيديو
        player.ready(function() {
            hls.attachMedia(player.el());
        });
    }
</script>

</body>
</html>
