<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الفيديوهات</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <h2>قائمة الفيديوهات</h2>
    <ul>
        @foreach($videos as $video)
            <li>
                <a href="{{ route('play.video', ['video' => $video]) }}">{{ $video }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
