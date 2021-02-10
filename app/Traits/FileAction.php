<?php

namespace App\Traits;


trait FileAction
{
    public function uploadFile($file, $path)
    {

        $underscoredName = str_replace(' ', '_', $file->getClientOriginalName());
        $filename = time() . '_' . $underscoredName;
        $imagePath = $file->storeAs('files', $filename, 'public');

        return $imagePath;
    }
}
