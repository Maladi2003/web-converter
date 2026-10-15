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

        $storagePath = storage_path('app/public');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // 1. Fitur Kompresi Gambar
        if ($action === 'compress') {
            $img = Image::read($file);
            $filename = 'compressed_' . time() . '.jpg';
            $outputPath = $storagePath . '/' . $filename;

            $img->toJpeg(60)->save($outputPath);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $filename),
                    'filename' => $filename
                ]);
            }

            return response()->download($outputPath, 'hasil-kompresi.jpg');
        }

        // 2. Fitur Hapus Background AI
        if ($action === 'remove_bg') {
            $inputPath = $file->getRealPath();
            $filename = 'nobg_' . time() . '.png';
            $outputPath = $storagePath . '/' . $filename;

            $model = $request->input('mode', 'birefnet-general');
            $allowedModels = ['birefnet-general', 'birefnet-portrait'];
            if (!in_array($model, $allowedModels)) {
                $model = 'birefnet-general';
            }

            $output = [];
            $returnCode = 0;
            exec("rembg i -m " . escapeshellarg($model) . " " . escapeshellarg($inputPath) . " " . escapeshellarg($outputPath) . " 2>&1", $output, $returnCode);

            if (file_exists($outputPath)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'url' => asset('storage/' . $filename),
                        'filename' => 'hasil-no-bg-' . time() . '.png'
                    ]);
                }
                return response()->download($outputPath, 'hasil-no-bg.png');
            }

            $errorDetails = implode(" ", $output);
            return response()->json(['success' => false, 'message' => $errorDetails], 500);
        }

        return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
    }
}