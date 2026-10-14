<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Laravel\Facades\Image;

class ConverterController extends Controller
{
    public function process(Request $request)
    {
        $file = $request->file('image');
        $action = $request->input('action');

        if ($action === 'compress') {
            $img = Image::read($file);
            $path = storage_path('app/public/compressed.jpg');
            $img->save($path, quality: 60);

            return response()->download($path);
        }

        if ($action === 'remove_bg') {
            $response = Http::withHeaders([
                'X-Api-Key' => 'a3kX5DrCcramFdXe6pKEjEr8',
            ])->attach(
                'image_file', file_get_contents($file), $file->getClientOriginalName()
            )->post('https://api.remove.bg/v1.0/removebg');

            $path = storage_path('app/public/no-bg.png');
            file_put_contents($path, $response->body());

            return response()->download($path);
        }
    }
}