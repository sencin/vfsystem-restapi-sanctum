<?php

namespace App\Http\Controllers;


class FileController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/public/images/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file($path);
    }
}
