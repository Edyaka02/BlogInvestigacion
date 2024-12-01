<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\Articulo;
use App\Models\Autor;
use App\Http\Controllers\ArticuloController;

class ArticuloControllerTest extends TestCase
{
    //use RefreshDatabase;

    public function test_store_method()
    {
        // Simular el almacenamiento
        Storage::fake('public');

        // Crear autores de prueba manualmente
        $autor = Autor::create([
            'NOMBRE_AUTOR' => 'John',
            'APELLIDO_AUTOR' => 'Doe',
        ]);

        // Datos de prueba
        $data = [
            'ISSN_ARTICULO' => '8888-5676',
            'TITULO_ARTICULO' => 'Título de prueba',
            'RESUMEN_ARTICULO' => 'Resumen de prueba',
            'FECHA_ARTICULO' => '2023-01-01',
            'REVISTA_ARTICULO' => 'Revista de prueba',
            'TIPO_ARTICULO' => 'Tipo de prueba',
            'URL_REVISTA_ARTICULO' => 'https://example.com',
            'URL_ARTICULO' => 'https://example.com/documento.pdf',
            'autores' => [$autor->ID_AUTOR],
            'pdf' => UploadedFile::fake()->create('documento.pdf', 1024, 'application/pdf'),
            'image' => UploadedFile::fake()->image('imagen.jpg', 600, 600),
        ];

        // Crear una instancia de Request con los datos de prueba
        // Crear una instancia de Request con los datos de prueba
        $request = Request::create('/articulos', 'POST', $data);

        // Adjuntar archivos al request
        $request->files->set('pdf', $data['pdf']);
        $request->files->set('image', $data['image']);

        // Crear una instancia del controlador
        $controller = new ArticuloController();

        // Llamar al método store del controlador
        $response = $controller->store($request);

        // Verificar que la redirección sea correcta
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('articulos.articulo'), $response->headers->get('Location'));

        // Verificar que el artículo se haya creado en la base de datos
        $this->assertDatabaseHas('tb_articulo', [
            'ISSN_ARTICULO' => '1234-5678',
            'TITULO_ARTICULO' => 'Título de prueba',
        ]);


        // // Verificar que los archivos se hayan almacenado
        // Storage::disk('public')->assertExists('pdfs/' . $data['pdf']->hashName());
        // Storage::disk('public')->assertExists('imagenes/' . $data['image']->hashName());
    }
}
