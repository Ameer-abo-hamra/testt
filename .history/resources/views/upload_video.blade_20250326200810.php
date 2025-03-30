<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع فيديو</title>
</head>

<body>
    <h2>رفع فيديو</h2>
    <form action="{{ route('upload.video') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept="video/*" required>
        <input type="text" name="" id="">
        <button type="submit">رفع</button>
    </form>
</body>

</html>
