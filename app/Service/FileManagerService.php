<?php
namespace App\Service;
use Illuminate\Support\Facades\File;

class FileManagerService{
    public function uploadFile($file){
      logger('Uploading file:', ['file' => $file]);
      $path = $this->saveFiletoStorage($file);
      return $path;
    }

    public function getPublicImage($filename){
        $path = storage_path('app/public/images/' . $filename);
        if (File::exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Public Image Not Found');
    }

    public function getPrivateImage($filename){
        $path = storage_path('app/private/images/' . $filename);

        if (File::exists($path)) {
            return response()->file($path);
        }
        abort(404, 'Private Image Not Found');
    }

    public function saveFiletoStorage($file){
      $originalName = $file->getClientOriginalName();
      $sanitizedOriginalName = preg_replace('/[^A-Za-z0-9.\-_]/', '', str_replace(' ', '_', trim($originalName)));
      $uniqueName = time() . '_' . $sanitizedOriginalName;
      $path = $file->storeAs('/files', $sanitizedOriginalName);

      return [
        'original' => $originalName,
        'unique' => $uniqueName,
        'path' => $path,
      ];

    }
}
