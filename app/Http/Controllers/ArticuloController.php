<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    // public function index()
    // {
    //     $articulos = Articulo::with('autores')->get();
    //     return view('articulos.articulo', compact('articulos'));
    // }
    public function index()
    {
        $articulos = Articulo::with('autores')
            ->select('ID_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'URL_IMAGEN_ARTICULO')
            ->get();
        return view('articulos.articulo', compact('articulos'));
    }

    public function show($id)
    {
        $articulo = Articulo::with('autores')->findOrFail($id);

        // Generar URLs públicas
        $articulo->URL_ARTICULO = Storage::url($articulo->URL_ARTICULO);
        $articulo->URL_IMAGEN_ARTICULO = Storage::url($articulo->URL_IMAGEN_ARTICULO);

        return view('articulos.articulo_detalle', compact('articulo'));
    }

    public function create()
    {
        $autorController = new AutorController();
        $autores = $autorController->index()->getData()['autores'];
        return view('articulos.create', compact('autores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ISSN_ARTICULO' => 'required|unique:tb_articulo',
            'TITULO_ARTICULO' => 'required|string|max:255',
            'RESUMEN_ARTICULO' => 'required|string',
            'FECHA_ARTICULO' => 'required|date',
            'REVISTA_ARTICULO' => 'required|string|max:100',
            'TIPO_ARTICULO' => 'required|string|max:100',
            'URL_REVISTA_ARTICULO' => 'required|url',
            'URL_ARTICULO' => 'required|url',
            'autores' => 'required|array',
            'pdf' => 'required|mimes:pdf', // 10 MB
            'image' => 'required|image|mimes:jpg,jpeg,png,gif', // 2 MB
        ]);

        // Subir el archivo PDF
        $pdfPath = $request->file('pdf')->store('public/pdfs');
        //$pdfUrl = Storage::url($pdfPath);

        // Subir la imagen
        $imagePath = $request->file('image')->store('public/imagenes');
        //$imageUrl = Storage::url($imagePath);

        // Crear el artículo y guardar las rutas de los archivos
        $articulo = Articulo::create([
            'ISSN_ARTICULO' => $request->ISSN_ARTICULO,
            'TITULO_ARTICULO' => $request->TITULO_ARTICULO,
            'RESUMEN_ARTICULO' => $request->RESUMEN_ARTICULO,
            'FECHA_ARTICULO' => $request->FECHA_ARTICULO,
            'REVISTA_ARTICULO' => $request->REVISTA_ARTICULO,
            'TIPO_ARTICULO' => $request->TIPO_ARTICULO,
            'URL_REVISTA_ARTICULO' => $request->URL_REVISTA_ARTICULO,
            'URL_ARTICULO' => $pdfPath,
            'URL_IMAGEN_ARTICULO' => $imagePath,
        ]);

        // Si necesitas guardar los autores u otros datos, hazlo aquí
        $articulo->autores()->sync($request->autores);

        return redirect()->route('articulos.articulo');
    }

    public function edit(Articulo $articulo)
    {
        $autores = Autor::all();
        return view('articulos.edit', compact('articulo', 'autores'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'ISSN_ARTICULO' => 'required|unique:tb_articulo,ISSN_ARTICULO,' . $articulo->ID_ARTICULO . ',ID_ARTICULO',
            'TITULO_ARTICULO' => 'required|string|max:255',
            'RESUMEN_ARTICULO' => 'required|string',
            'FECHA_ARTICULO' => 'required|date',
            'REVISTA_ARTICULO' => 'required|string|max:100',
            'TIPO_ARTICULO' => 'required|string|max:100',
            'URL_REVISTA_ARTICULO' => 'required|url',
            'URL_ARTICULO' => 'required|url',
            'URL_IMAGEN_ARTICULO' => 'required|url',
            'autores' => 'required|array'
        ]);

        $articulo->update($request->all());
        $articulo->autores()->sync($request->autores);

        return redirect()->route('articulos.index');
    }

    public function destroy(Articulo $articulo)
    {
        $articulo->delete();
        return redirect()->route('articulos.articulo');
    }
}
