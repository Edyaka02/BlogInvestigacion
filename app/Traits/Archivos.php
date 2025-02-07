<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Archivos
{
    private function getUniqueFileName($file, $directory)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '.' . $extension;
        $counter = 1;

        while (Storage::exists($directory . '/' . $fileName)) {
            $fileName = $originalName . '(' . $counter . ').' . $extension;
            $counter++;
        }

        return $fileName;
    }
}