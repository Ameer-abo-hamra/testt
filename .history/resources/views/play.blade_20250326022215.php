<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاهدة الفيديو</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>

<body>
    <h2>تشغيل الفيديو</h2>
    <video id="video" controls></video>

    <script>
        var video = document.getElementById('video');
        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource("{{ asset('converted_video/' . 'converted_video/master_1_500.m3u8 ') }}");
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = "{{ asset() }}";
            video.play();
        }
    </script>
</body>

</html>
