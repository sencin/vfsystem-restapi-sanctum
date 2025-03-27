<?php
 namespace App\Service;
 use Illuminate\Http\UploadedFile;

 class ImageService{
    public function deleteOldImage(?string $oldImage): void
    {
        if ($oldImage) {
            $oldImagePath = storage_path('app/public/images/' . $oldImage);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    public function uploadImage(?UploadedFile $newImage): ?string
    {
        if ($newImage) {
            $filename = $newImage->hashName();
            $newImage->store('images', 'public');
            return $filename;
        }

        return null;
    }



}
