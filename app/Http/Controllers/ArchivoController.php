<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,webp,png|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('public/archivos');

        // Aquí puedes guardar la ruta del archivo en la base de datos, si es necesario

        return back()->with('success', 'Archivo subido exitosamente.');
    }
}
