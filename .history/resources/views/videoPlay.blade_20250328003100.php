<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
</head>
<body>

    <h2>اختر الجودة</h2>
    <select id="qualitySelector">
        @foreach ($videoFiles as $file)
            <option value="{{ asset('streamable_videos/' . $file) }}">
                {{ str_replace(["$video_id", "_", ".m3u8"], ["", " ", ""], $file) }}p
            </option>
        @endforeach
    </select>

    <video id="videoPlayer" controls>
        <source id="videoSource" src="{{ asset('streamable_videos/' . $videoFiles[0]) }}" type="application/x-mpegURL">
        Your browser does not support the video tag.
    </video>

    <script>
        document.getElementById("qualitySelector").addEventListener("change", function() {
            let video = document.getElementById("videoPlayer");
            let source = document.getElementById("videoSource");
            source.src = this.value;
            video.load(); // إعادة تحميل الفيديو بالجودة المختارة
        });
    </script>

</body>
</html>
