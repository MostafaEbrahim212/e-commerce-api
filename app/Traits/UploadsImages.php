<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsImages
{
    /**
     * Upload an image and return the filename.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $oldImage
     * @return string|null
     */
    protected function uploadImage(UploadedFile $file, $folder, $oldImage = null)
    {
        if ($oldImage && Storage::exists("public/images/$folder/$oldImage")) {
            Storage::delete("public/images/$folder/$oldImage");
        }
        $imageName = time() . '.' . $file->extension();
        $path = $file->storeAs("public/images/$folder", $imageName);
        return $imageName;
    }
}
