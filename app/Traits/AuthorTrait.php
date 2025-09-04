<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\Core\Autor;

trait AuthorTrait
{
    /**
     * Divide el nombre completo del autor en nombre y apellido.
     */
    private function splitAuthorName($autor)
    {
        $nombres = explode(' ', $autor->NOMBRE_AUTOR);
        $apellidos = explode(' ', $autor->APELLIDO_AUTOR);
        $autor->NOMBRE_AUTOR = $nombres[0];
        $autor->APELLIDO_AUTOR = $apellidos[0];
    }

    /**
     * Maneja la carga de autores.
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
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

    
}