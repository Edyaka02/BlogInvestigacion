<?php

namespace App\Traits;

trait AutorTrait
{
    public function splitAuthorName($autor)
    {
        $nombres = explode(' ', $autor->NOMBRE_AUTOR);
        $apellidos = explode(' ', $autor->APELLIDO_AUTOR);
        $autor->NOMBRE_AUTOR = $nombres[0];
        $autor->APELLIDO_AUTOR = $apellidos[0];
    }
}