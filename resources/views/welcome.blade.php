<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OmniMedia Studio - AI Background Remover & Converter</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-container": "#8083ff",
                        "surface-bright": "#31394d",
                        "surface": "#0b1326",
                        "on-surface": "#dae2fd",
                        "primary": "#c0c1ff",
                        "surface-container-low": "#131b2e",
                        "secondary-container": "#571bc1",
                        "secondary": "#d0bcff",
                        "background": "#0b1326",
                        "surface-container-high": "#222a3d",
                        "surface-container": "#171f33",
                        "outline-variant": "#464554",
                        "surface-container-highest": "#2d3449",
                        "on-surface-variant": "#c7c4d7",
                        "surface-container-lowest": "#060e20",
                        "on-primary": "#1000a9",
                        "tertiary": "#4edea3"
                    },
                    spacing: {
                        "space-sm": "0.5rem", "space-lg": "1.5rem", "space-md": "1rem", "space-xl": "2.5rem", "gutter": "1.5rem", "space-xs": "0.25rem"
                    },
                    fontFamily: {
                        "body-md": ["Plus Jakarta Sans"], "headline-sm": ["Plus Jakarta Sans"]
                    }
                }
            }
        };
    </script>
    <style>::-webkit-scrollbar { display: none; }</style>
</head>

<body class="bg-background font-body-md text-on-surface antialiased min-h-screen">

<header class="fixed top-0 left-0 right-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl border-b border-surface-container-highest">
    <div class="h-16 w-full px-gutter flex items-center justify-between">
        <div class="flex items-center gap-space-sm">
            <span class="material-symbols-outlined text-primary text-[28px]">auto_awesome</span>
            <span class="font-bold text-lg text-on-surface">OmniMedia Studio</span>
            <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-data text-xs uppercase">PRO</span>
        </div>
    </div>
</header>

<main class="w-full pt-20 pb-16 bg-background min-h-screen">
    <div class="w-full max-w-7xl mx-auto px-gutter flex flex-col gap-space-xl">
        
        <!-- Form Pengunggahan -->
        <form id="laravelForm" onsubmit="return false;">
            <section class="w-full">
                <div class="relative rounded-2xl bg-surface-container-low/60 backdrop-blur-xl p-space-lg md:p-space-xl flex flex-col items-center text-center shadow-xl border border-surface-container-high">
                    
                    <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-surface-container-highest shadow-inner mb-space-md">
                        <span class="material-symbols-outlined text-primary text-[36px]">cloud_upload</span>
                    </div>

                    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-space-xs font-bold">
                        Pilih Gambar dari Perangkat Anda
                    </h3>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mb-space-md">
                        Unggah foto pasfoto, dokumen, atau produk untuk memproses
                    </p>

                    <div class="flex flex-col md:flex-row items-center justify-center gap-space-md w-full max-w-xl mb-space-lg">
                        <div class="w-full md:w-1/2 text-left">
                            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">1. File Gambar:</label>
                            <input type="file" name="image" id="realFileInput" required accept="image/*" class="w-full text-sm text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-fixed cursor-pointer">
                        </div>

                        <div class="w-full md:w-1/2 text-left">
                            <label class="font-label-md text-label-md text-on-surface-variant mb-1 block">2. Model AI (BiRefNet):</label>
                            <select name="mode" id="modeSelect" class="w-full px-space-md py-2 rounded-lg bg-surface-container-highest text-on-surface font-label-md text-label-md border border-outline-variant/30">
                                <option value="birefnet-general" selected>BiRefNet General (Dokumen & Meterai)</option>
                                <option value="birefnet-portrait">BiRefNet Portrait (Pasfoto & Orang)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Eksekusi -->
                    <div class="flex flex-wrap items-center justify-center gap-space-md mb-space-md">
                        <button type="button" id="btnRemoveBg" onclick="processAI('remove_bg')" class="px-space-lg py-space-sm rounded-lg bg-tertiary text-on-tertiary-container font-label-lg font-bold shadow-md hover:opacity-90 transition-all flex items-center gap-space-xs cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">auto_fix_high</span>
                            Hapus Background AI
                        </button>
                        <button type="button" id="btnCompress" onclick="processAI('compress')" class="px-space-lg py-space-sm rounded-lg bg-primary text-on-primary font-label-lg font-bold shadow-md hover:opacity-90 transition-all flex items-center gap-space-xs cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">compress</span>
                            Kompres Gambar
                        </button>
                    </div>

                    <!-- Indikator Progres Persentase -->
                    <div id="progressContainer" class="hidden w-full max-w-md mt-4 flex flex-col gap-2">
                        <div class="flex justify-between text-sm font-semibold">
                            <span id="progressStatusText" class="text-tertiary">⏳ Memproses AI...</span>
                            <span id="progressPercent" class="text-tertiary">0%</span>
                        </div>
                        <div class="w-full h-3 bg-surface-container-highest rounded-full overflow-hidden shadow-inner">
                            <div id="progressBar" class="h-full bg-gradient-to-r from-primary via-secondary to-tertiary rounded-full transition-all duration-300" style="width: 0%;"></div>
                        </div>
                    </div>

                </div>
            </section>
        </form>

        <!-- Area Preview Hasil Pembanding AI -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start" id="section-bg-ai">
            <div class="lg:col-span-12 flex flex-col gap-space-md p-space-lg rounded-2xl bg-surface-container-low/80 backdrop-blur-xl shadow-xl border border-surface-container-high">
                <div class="flex items-center justify-between pb-space-xs">
                    <div class="flex items-center gap-space-sm">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container text-on-secondary-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">compare</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Hasil Pembandingan</h2>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Geser pembatas untuk melihat perbedaan sebelum dan sesudah</span>
                        </div>
                    </div>

                    <!-- Tombol Download -->
                    <a id="btnDownload" href="#" download class="opacity-50 pointer-events-none px-space-lg py-space-sm rounded-lg bg-tertiary text-on-tertiary-container font-label-lg font-bold shadow-md transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        <span>Unduh Hasil HD</span>
                    </a>
                </div>

                <!-- Canvas Pembanding Presisi (Awal Garis di 100% / Paling Kanan) -->
                <div class="relative w-full h-[500px] rounded-xl overflow-hidden select-none bg-surface-container-lowest shadow-inner border border-surface-container-highest" id="compare-container">
                    
                    <!-- Layer 1: Gambar Sebelum -->
                    <img id="imgBefore" alt="Sebelum" class="absolute inset-0 w-full h-full object-contain p-2 z-0" src="https://via.placeholder.com/800x600/131b2e/c7c4d7?text=Before"/>

                    <!-- Layer 2: Gambar Sesudah dengan Background Catur Solid & Clip-Path Awal 0% Cut (Tampil 100%) -->
                    <div id="compare-after" class="absolute inset-0 w-full h-full z-10 bg-[#171f33]" style="clip-path: inset(0 0% 0 0); background-image: linear-gradient(45deg, #222a3d 25%, transparent 25%), linear-gradient(-45deg, #222a3d 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #222a3d 75%), linear-gradient(-45deg, transparent 75%, #222a3d 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0px;">
                        <img id="imgAfter" alt="Sesudah" class="absolute inset-0 w-full h-full object-contain p-2 z-10" src="https://via.placeholder.com/800x600/171f33/4edea3?text=After"/>
                    </div>

                    <!-- Label -->
                    <span class="absolute top-space-md right-space-md px-space-sm py-1 rounded-md bg-surface-container-lowest/80 backdrop-blur-md text-on-surface-variant font-label-md shadow-sm z-20 pointer-events-none">
                        Sebelum
                    </span>
                    <span class="absolute top-space-md left-space-md px-space-sm py-1 rounded-md bg-surface-container-lowest/80 backdrop-blur-md text-tertiary font-label-md shadow-sm font-semibold z-20 pointer-events-none">
                        Sesudah
                    </span>

                    <!-- Garis Penggeser (Awal di 100%) -->
                    <div id="compare-divider" class="absolute top-0 bottom-0 left-[100%] w-0.5 bg-white shadow-[0_0_10px_rgba(0,0,0,0.8)] cursor-ew-resize flex items-center justify-center -translate-x-1/2 z-30 pointer-events-none">
                        <div class="w-8 h-8 rounded-full bg-surface-bright text-on-surface flex items-center justify-center shadow-lg border border-white/20">
                            <span class="material-symbols-outlined text-[18px]">drag_indicator</span>
                        </div>
                    </div>

                    <!-- Input Range (Value Awal 100) -->
                    <input type="range" id="compare-slider" min="0" max="100" value="100" aria-label="Bandingkan Gambar" class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-40">
                </div>
            </div>
        </section>

    </div>
</main>

<script>
    let progressInterval;

    function processAI(actionType) {
        const fileInput = document.getElementById('realFileInput');
        if (!fileInput || !fileInput.files.length) {
            alert('⚠️ Harap pilih berkas gambar terlebih dahulu pada tombol "Choose File"!');
            return;
        }

        const file = fileInput.files[0];

        // Preview foto Sebelum secara instan
        const originalUrl = URL.createObjectURL(file);
        document.getElementById('imgBefore').src = originalUrl;

        // Reset tombol Download
        const btnDownload = document.getElementById('btnDownload');
        btnDownload.classList.add('opacity-50', 'pointer-events-none');
        btnDownload.removeAttribute('download');

        // Tampilkan Baris Progres
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const progressPercent = document.getElementById('progressPercent');
        const progressStatusText = document.getElementById('progressStatusText');

        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressPercent.innerText = '0%';
        progressStatusText.innerText = '⏳ Mengunggah berkas...';

        const btnRemove = document.getElementById('btnRemoveBg');
        const btnCompress = document.getElementById('btnCompress');
        btnRemove.disabled = true;
        btnCompress.disabled = true;

        let currentProgress = 0;
        clearInterval(progressInterval);
        progressInterval = setInterval(() => {
            if (currentProgress < 90) {
                currentProgress += Math.floor(Math.random() * 5) + 2;
                if (currentProgress > 90) currentProgress = 90;
                progressBar.style.width = currentProgress + '%';
                progressPercent.innerText = currentProgress + '%';

                if (currentProgress > 20 && currentProgress < 60) {
                    progressStatusText.innerText = '⚙️ Mesin BiRefNet sedang memproses...';
                } else if (currentProgress >= 60) {
                    progressStatusText.innerText = '✨ Menyempurnakan batas tepi gambar...';
                }
            }
        }, 300);

        // Ambil CSRF Token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        const formData = new FormData();
        formData.append('_token', token);
        formData.append('image', file);
        formData.append('action', actionType);
        formData.append('mode', document.getElementById('modeSelect').value);

        fetch('/process', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error('Server Error (' + response.status + '): ' + text); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressPercent.innerText = '100%';
                progressStatusText.innerText = '✅ Pemrosesan Selesai!';

                // Tampilkan Foto Sesudah
                document.getElementById('imgAfter').src = data.url;

                // Reset Garis Pembatas ke Posisi Paling Kanan (100%)
                document.getElementById('compare-slider').value = 100;
                document.getElementById('compare-after').style.clipPath = 'inset(0 0% 0 0)';
                document.getElementById('compare-divider').style.left = '100%';

                // Aktifkan Tombol Download
                btnDownload.href = data.url;
                btnDownload.setAttribute('download', data.filename || 'hasil-no-bg.png');
                btnDownload.classList.remove('opacity-50', 'pointer-events-none');

                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                }, 2500);

                document.getElementById('section-bg-ai').scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Gagal memproses gambar: ' + (data.message || 'Error tidak diketahui'));
                clearInterval(progressInterval);
                progressContainer.classList.add('hidden');
            }
        })
        .catch(error => {
            alert('Terjadi kendala saat mengirim gambar:\n' + error.message);
            clearInterval(progressInterval);
            progressContainer.classList.add('hidden');
        })
        .finally(() => {
            btnRemove.disabled = false;
            btnCompress.disabled = false;
        });
    }

    // Logic Penggeser Slider Presisi (Clip-Path)
    const compareSlider = document.getElementById('compare-slider');
    const compareAfter = document.getElementById('compare-after');
    const compareDivider = document.getElementById('compare-divider');

    if (compareSlider) {
        compareSlider.addEventListener('input', (e) => {
            const val = e.target.value;
            compareAfter.style.clipPath = `inset(0 ${100 - val}% 0 0)`;
            compareDivider.style.left = `${val}%`;
        });
    }
</script>

</body>
</html>