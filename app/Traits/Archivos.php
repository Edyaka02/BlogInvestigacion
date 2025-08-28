<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Core\Autor;

trait Archivos
{
    // private function getUniqueFileName($file, $directory)
    // {
    //     $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //     $extension = $file->getClientOriginalExtension();
    //     $fileName = $originalName . '.' . $extension;
    //     $counter = 1;

    //     // while (Storage::exists($directory . '/' . $fileName)) {
    //     //     $fileName = $originalName . '(' . $counter . ').' . $extension;
    //     //     $counter++;
    //     // }
    //     // ✅ Cambiar para usar storage privado
    //     while (Storage::exists($directory . '/' . $fileName)) {
    //         $fileName = $originalName . '(' . $counter . ').' . $extension;
    //         $counter++;
    //     }

    //     return $fileName;
    // }

    // private function handleFileUpload(Request $request, $fieldName, $directory)
    // {
    //     if ($request->hasFile($fieldName)) {
    //         $file = $request->file($fieldName);
    //         $fileName = $this->getUniqueFileName($file, $directory);
    //         // $filePath = $file->storeAs('public/' . $directory, $fileName);
    //         // return  $directory . '/' . $fileName;
    //         // ✅ GUARDAR EN STORAGE PRIVADO (sin 'public/')
    //         $filePath = $file->storeAs($directory, $fileName);
    //         return $filePath; // Devuelve: "articulos/archivo.pdf"
    //     }
    //     return null;
    // }

    private function getUniqueFileName($file, $directory)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '.' . $extension;
        $counter = 1;

        // ✅ CAMBIO: Nueva estructura storage/articulos/
        $publicPath = public_path('storage/' . $directory);

        while (File::exists($publicPath . '/' . $fileName)) {
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

            // ✅ CAMBIO: Guardar en public/storage/articulos/
            $publicPath = public_path('storage/' . $directory);

            // ✅ CREAR: Directorio si no existe
            if (!File::exists($publicPath)) {
                File::makeDirectory($publicPath, 0755, true);
            }

            // ✅ MOVER: Archivo a public/storage/
            $file->move($publicPath, $fileName);

            // ✅ RETORNAR: Ruta relativa para la base de datos
            return 'storage/' . $directory . '/' . $fileName;
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

    // private function applyYears($num)
    // {
    //     return range(date('Y'), date('Y') - $num);
    // }
}
