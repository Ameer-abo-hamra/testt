<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع الفيديوهات</title>
</head>
<body>
    <h2>رفع الفيديو</h2>
    <form action="{{ route('upload.video') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="video" required>
        <button type="submit">رفع الفيديو</button>
    </form>
</body>
</html>
