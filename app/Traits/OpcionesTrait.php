<?php

namespace App\Traits;

trait OpcionesTrait
{
    public function getConfig(array $keys = [])
    {
        $config = [
            'tiposArticulos' => config('tipos.articulos'),
            'tiposEventos' => config('tipos.eventos'),
            'ambitos' => config('tipos.ambitos'),
            'modalidades' => config('tipos.modalidad'),
            'comunicacion' => config('tipos.comunicacion'),
            'organismos' => config('tipos.organismos'),
        ];

        // Filtrar configuraciones si se especifican claves
        if (!empty($keys)) {
            return array_filter($config, function ($key) use ($keys) {
                return in_array($key, $keys);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $config;
    }
}