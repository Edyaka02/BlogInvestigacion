<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Autor;

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

    private function handleFileUpload(Request $request, $fieldName, $directory)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = $this->getUniqueFileName($file, $directory);
            $filePath = $file->storeAs('public/' . $directory, $fileName);
            return  $directory . '/' . $fileName;
        }
        return null;
    }

    private function handleAutores(Request $request)
    {
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        return $autores;
    }

    private function applyYears($num)
    {
        return range(date('Y'), date('Y') - $num);
    }
}
