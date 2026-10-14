<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class ConverterController extends Controller
{
    public function process(Request $request)
    {
        $file = $request->file('image');
        $action = $request->input('action');

        // 1. Pastikan direktori penyimpanan storage/app/public sudah dibuat
        $storagePath = storage_path('app/public');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // 2. Logika Kompresi Gambar
        if ($action === 'compress') {
            $img = Image::read($file);
            $filename = 'compressed_' . time() . '.jpg';
            $outputPath = $storagePath . '/' . $filename;

            $img->toJpeg(60)->save($outputPath);

            return response()->download($outputPath, 'hasil-kompresi.jpg')->deleteFileAfterSend(true);
        }

        // 3. Logika Hapus Background AI
        if ($action === 'remove_bg') {
            $inputPath = $file->getRealPath();
            $filename = 'nobg_' . time() . '.png';
            $outputPath = $storagePath . '/' . $filename;

            // Eksekusi perintah rembg dan tangkap pesan output/error dari terminal
            $output = [];
            $returnCode = 0;
            exec("rembg i -m birefnet-general -a " . escapeshellarg($inputPath) . " " . escapeshellarg($outputPath) . " 2>&1", $output, $returnCode);
            // Pengecekan: Apakah berkas berhasil dibuat oleh rembg?
            if (file_exists($outputPath)) {
                return response()->download($outputPath, 'hasil-no-bg.png')->deleteFileAfterSend(true);
            }

            // Jika berkas tidak ditemukan, tampilkan pesan eror detail
            $errorDetails = implode(" ", $output);
            return response("Gagal memproses gambar AI. Detail eror Python/Rembg: <br><pre>" . $errorDetails . "</pre>", 500);
        }
    }
}