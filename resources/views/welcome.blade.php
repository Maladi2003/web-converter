<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Web Converter & Remove BG</title>
</head>
<body>
    <h2>Pengolah Gambar</h2>
    <form action="/process" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <br><br>
        <button type="submit" name="action" value="compress">Kompres Gambar</button>
        <button type="submit" name="action" value="remove_bg">Hapus Background AI</button>
    </form>
</body>
</html>