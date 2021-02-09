<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileAction
{
    public function uploadFile($file, $path)
    {

        $filename = rand() . '.' . $file->getClientOriginalExtension();

        // Manually specify a filename...
        Storage::putFileAs($file, new File($path), $filename);

        return $filename;
    }
}
