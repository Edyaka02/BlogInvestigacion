<?php
// filepath: c:\laragon\www\BlogInvestigacion\app\Traits\Archivos.php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

trait FilesTrait
{
    /**
     * Crea la carpeta para una entidad por ID.
     * 
     * @param string $entity - Nombre de la entidad (articulos, usuarios, etc.)
     * @param int $id - ID de la entidad
     * @return array - Rutas de la carpeta creada
     */
    private function createEntityDirectory($entity, $id)
    {
        $entityDir = "storage/{$entity}/{$id}";
        $entityPath = public_path($entityDir);
        // $entityPath = storage_path($entityDir);

        // Crear directorio si no existe
        if (!File::exists($entityPath)) {
            File::makeDirectory($entityPath, 0755, true);
        }

        return [
            'dir' => $entityDir,
            'path' => $entityPath
        ];
    }

    /**
     * Obtiene un nombre de archivo único para evitar colisiones.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $fullPath - Ruta completa donde se guardará
     * @return string
     */
    private function getUniqueFileName($file, $fullPath)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // Limpiar nombre del archivo
        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $fileName = $cleanName . '.' . $extension;
        $counter = 1;

        while (File::exists($fullPath . '/' . $fileName)) {
            $fileName = $cleanName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $fileName;
    }

    /**
     * Maneja la carga de archivos - VERSIÓN SIMPLIFICADA.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $fieldName
     * @param string $entity
     * @param int $entityId
     * @return string|null
     */
    private function handleFileUploadSimple(Request $request, $fieldName, $entity, $entityId)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            // Crear carpeta de la entidad
            $directory = $this->createEntityDirectory($entity, $entityId);

            // Obtener nombre único
            $fileName = $this->getUniqueFileName($file, $directory['path']);

            // Mover archivo
            $file->move($directory['path'], $fileName);

            // Retornar ruta relativa para la base de datos
            return $directory['dir'] . '/' . $fileName;
        }

        return null;
    }

    /**
     * Maneja múltiples archivos en la misma carpeta.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $entity
     * @param int $entityId
     * @param array $fileFields - ['field_name']
     * @return array
     */
    private function handleMultipleFileUploadsSimple(Request $request, $entity, $entityId, $fileFields)
    {
        $uploadedFiles = [];

        foreach ($fileFields as $fieldName) {
            $filePath = $this->handleFileUploadSimple($request, $fieldName, $entity, $entityId);
            if ($filePath) {
                $uploadedFiles[$fieldName] = $filePath;
            }
        }

        return $uploadedFiles;
    }

    /**
     * Elimina archivo y limpia directorios vacíos.
     * 
     * @param string $filePath
     * @return bool
     */
    private function deleteFile($filePath)
    {
        if ($filePath) {
            $fullPath = public_path($filePath);

            if (File::exists($fullPath)) {
                File::delete($fullPath);

                // Limpiar directorio vacío si no tiene más archivos
                $this->cleanEmptyDirectories(dirname($fullPath));

                return true;
            }
        }

        return false;
    }

    /**
     * Limpia directorios vacíos recursivamente.
     * 
     * @param string $dir
     * @return void
     */
    private function cleanEmptyDirectories($dir)
    {
        if (File::isDirectory($dir)) {
            $files = File::files($dir);
            $directories = File::directories($dir);

            // Si está vacío y no es el directorio base de storage
            if (empty($files) && empty($directories) && !str_ends_with($dir, 'storage')) {
                File::deleteDirectory($dir);

                // Limpiar directorio padre también si está vacío
                $this->cleanEmptyDirectories(dirname($dir));
            }
        }
    }

    /**
     * Obtiene todos los archivos de una entidad.
     * 
     * @param string $entity
     * @param int $entityId
     * @return array
     */
    private function getEntityFiles($entity, $entityId)
    {
        $entityDir = public_path("storage/{$entity}/{$entityId}");
        $result = [
            'files' => [],
            'total_size' => 0
        ];

        if (File::exists($entityDir)) {
            $files = File::files($entityDir);
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']) ? 'image' : ($extension === 'pdf' ? 'pdf' : 'other');

                $result['files'][] = [
                    'name' => $file->getFilename(),
                    'path' => "storage/{$entity}/{$entityId}/" . $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_human' => $this->formatBytes($file->getSize()),
                    'type' => $type,
                    'extension' => $extension
                ];
                $result['total_size'] += $file->getSize();
            }
        }

        $result['total_size_human'] = $this->formatBytes($result['total_size']);

        return $result;
    }

    /**
     * Formatea bytes en formato humano.
     * 
     * @param int $bytes
     * @return string
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
