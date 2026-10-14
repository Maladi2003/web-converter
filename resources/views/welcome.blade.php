<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Web Converter & Remove BG AI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 450px; margin: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        button { padding: 10px 15px; margin-right: 10px; cursor: pointer; border: none; border-radius: 4px; color: white; }
        .btn-compress { background-color: #007bff; }
        .btn-removebg { background-color: #28a745; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Pengolah Gambar Web</h2>
        <form action="/process" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" required accept="image/*">
            <br><br>
            <button type="submit" name="action" value="compress" class="btn-compress">Kompres Gambar</button>
            <button type="submit" name="action" value="remove_bg" class="btn-removebg">Hapus Background AI</button>
        </form>
    </div>
</body>
</html>