<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://unpkg.com/video.js/dist/video-js.min.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Video Player</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .video-container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        video {
            width: 320px;
            height: 240px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <!-- تضمين مكتبة Video.js -->


    <video id="my-video" class="video-js vjs-default-skin" controls preload="auto" width="600" height="400">
        <source src="{{ asset('public\converted_video\x.mp4') }}" type="application/x-mpegURL">
    </video>

    <script>
        var player = videojs('my-video');
    </script>

</body>

</html>
