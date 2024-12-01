<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Autor;

class ArticuloController extends Controller
{
    public function index()
    {
        $articulos = Articulo::with('autores')->get();
        return view('articulos.articulo', compact('articulos'));
    }

    public function create()
    {
        $autores = Autor::all();
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
            'URL_IMAGEN_ARTICULO' => 'required|url',
            'autores' => 'required|array'
        ]);

        $articulo = Articulo::create($request->all());
        $articulo->autores()->sync($request->autores);

        return redirect()->route('articulos.index');
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

