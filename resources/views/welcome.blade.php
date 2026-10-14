<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Converter & Remove Background AI</title>
    <!-- Pustaka AI Remove Background Gratis & Unlimited -->
    <script src="https://cdn.jsdelivr.net/npm/@imgly/background-removal@1.5.5/dist/index.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        #loading { display: none; color: #007bff; font-weight: bold; margin-top: 10px; }
        img { max-width: 100%; border-radius: 6px; margin-top: 10px; border: 1px solid #ddd; }
        button { background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Pengolah Gambar Web</h2>
        
        <!-- Fitur Kompresi Gambar via Server Laravel -->
        <h3>1. Kompresi Gambar</h3>
        <form action="/process" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" required>
            <input type="hidden" name="action" value="compress">
            <br><br>
            <button type="submit">Kompres Gambar</button>
        </form>

        <hr style="margin: 25px 0;">

        <!-- Fitur Hapus Background AI via Browser (Unlimited Gratis) -->
        <h3>2. Hapus Background AI (Tanpa Limit)</h3>
        <input type="file" id="imageInput" accept="image/*">
        <p id="loading">Memproses AI di browser... (Proses pertama kali mengunduh model AI berukuran ~40 MB)</p>
        <div id="result"></div>
    </div>

    <script>
        const imageInput = document.getElementById('imageInput');
        const loading = document.getElementById('loading');
        const result = document.getElementById('result');

        imageInput.addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) return;

            loading.style.display = 'block';
            result.innerHTML = '';

            try {
                // Menjalankan pemrosesan AI langsung di peramban pengguna
                const blob = await imglyRemoveBackground(file);
                const url = URL.createObjectURL(blob);

                loading.style.display = 'none';
                result.innerHTML = `
                    <p><strong>Hasil Pemotongan:</strong></p>
                    <img src="${url}" alt="Hasil Remove BG">
                    <br><br>
                    <a href="${url}" download="hasil-no-bg.png">
                        <button type="button" class="btn-success">Unduh Gambar</button>
                    </a>
                `;
            } catch (error) {
                loading.style.display = 'none';
                alert('Terjadi kesalahan saat memproses gambar: ' + error.message);
            }
        });
    </script>
</body>
</html>