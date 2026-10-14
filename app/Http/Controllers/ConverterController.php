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

        // Controller ini sekarang khusus menangani kompresi gambar
        if ($action === 'compress') {
            $img = Image::read($file);
            $path = storage_path('app/public/compressed.jpg');
            $img->save($path, quality: 60);

            return response()->download($path);
        }
    }
}