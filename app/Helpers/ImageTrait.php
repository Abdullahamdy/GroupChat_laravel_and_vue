<?php

namespace App\Helpers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageTrait
{


    public function uploadImage(UploadedFile $file, $folder = 'images')
    {
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($folder, $fileName, 'public');
        return $filePath;
    }

    // Define the method to retrieve the full image URL
    public function getImageUrl($path)
    {
        return Storage::disk('public')->url($path);
    }
    public function uploadAndSaveImage($file)
    {
        $imagePath = $this->uploadImage($file);
        $this->image_path = '/storage/' . $imagePath;
        return $this->image_path;
    }


    public function getImageUrlAttribute()
    {
        return $this->getImageUrl($this->image_path);
    }

    public function removeImage()
    {
        $path = Str::replaceFirst('/storage', '', 'public' . $this->imagePath);
        if (Storage::exists($path)) {
            Storage::delete($path);
            echo 'Image removed successfully.';
        } else {
            echo 'Image does not exist or could not be removed.';
        }
    }
}
