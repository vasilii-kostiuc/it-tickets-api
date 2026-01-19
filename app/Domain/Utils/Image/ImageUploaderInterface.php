<?php

namespace App\Domain\Utils\Image;

use Illuminate\Http\UploadedFile;

interface ImageUploaderInterface
{
    public function uploadImage(string $fileName, UploadedFile $uploadedFile);
}
