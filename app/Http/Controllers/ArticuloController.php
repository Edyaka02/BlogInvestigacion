<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticuloController extends Controller
{
    public function index()
    {
        $articulos = Articulo::with('autores')
            ->select('ID_ARTICULO', 'TITULO_ARTICULO', 'FECHA_ARTICULO', 'URL_IMAGEN_ARTICULO')
            ->get();
        return view('articulos.articulo', compact('articulos'));
    }

    public function adminIndex(Request $request)
    {
        $query = Articulo::with('autores')->where('ELIMINADO_ARTICULO', false);

        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $search = Str::singular($search); // Normaliza la palabra clave a singular
            $query->where(function ($q) use ($search) {
                $q->where('TITULO_ARTICULO', 'like', "{$search}%")
                    ->orWhere('ISSN_ARTICULO', 'like', "{$search}%")
                    ->orWhere('REVISTA_ARTICULO', 'like', "{$search}%")
                    ->orWhereHas('autores', function ($q) use ($search) {
                        $q->where('NOMBRE_AUTOR', 'like', "{$search}%")
                            ->orWhere('APELLIDO_AUTOR', 'like', "{$search}%");
                    });
            });
        }

        if ($request->has('tipo') && !empty($request->input('tipo'))) {
            $query->whereIn('TIPO_ARTICULO', $request->input('tipo'));
        }

        // if ($request->has('anio') && !empty($request->input('anio'))) {
        //     $query->whereIn(DB::raw('YEAR(FECHA_ARTICULO)'), $request->input('anio'));
        // }

        // if ($request->has('anio') && !empty($request->input('anio'))) {
        //     $anios = $request->input('anio');
        //     if (!is_array($anios)) {
        //         $anios = [$anios];
        //     }
        //     $query->whereIn(DB::raw('YEAR(FECHA_ARTICULO)'), $anios);
        // }

        // if ($request->input('anio') === 'intervalo' && $request->has('anio_inicio') && $request->has('anio_fin') && !is_null($request->input('anio_inicio')) && !is_null($request->input('anio_fin'))) {
        //     $anio_inicio = $request->input('anio_inicio');
        //     $anio_fin = $request->input('anio_fin');
        //     $query->whereBetween(DB::raw('YEAR(FECHA_ARTICULO)'), [$anio_inicio, $anio_fin]);
        // }

        if ($request->input('anio') === 'intervalo' && $request->has('anio_inicio') && $request->has('anio_fin') && !is_null($request->input('anio_inicio')) && !is_null($request->input('anio_fin'))) {
            $anio_inicio = $request->input('anio_inicio');
            $anio_fin = $request->input('anio_fin');
            $query->whereBetween(DB::raw('YEAR(FECHA_ARTICULO)'), [$anio_inicio, $anio_fin]);
            // } elseif ($request->has('anio') && !empty($request->input('anio'))) {
        } elseif ($request->has('anio') && !empty($request->input('anio')) && $request->input('anio') !== 'todos') {
            $anios = $request->input('anio');
            if (!is_array($anios)) {
                $anios = [$anios];
            }
            $query->whereIn(DB::raw('YEAR(FECHA_ARTICULO)'), $anios);
        }

        // $ordenar = $request->input('ordenar', 'fecha_desc');
        // if ($request->has('ordenar') && !is_null($request->input('ordenar'))) {
        //     $ordenar = $request->input('ordenar');
        //     switch ($ordenar) {
        //         case 'titulo_asc':
        //             $query->orderBy('TITULO_ARTICULO', 'asc');
        //             break;
        //         case 'titulo_desc':
        //             $query->orderBy('TITULO_ARTICULO', 'desc');
        //             break;
        //         case 'fecha_asc':
        //             $query->orderBy('FECHA_ARTICULO', 'asc');
        //             break;
        //         case 'fecha_desc':
        //             $query->orderBy('FECHA_ARTICULO', 'desc');
        //             break;
        //     }
        // }
        $ordenar = $request->input('ordenar', 'fecha_desc'); // Establecer 'fecha_desc' como predeterminado
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_ARTICULO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_ARTICULO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_ARTICULO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_ARTICULO', 'desc');
                break;
        }

        $articulos = $query->paginate(20)->appends($request->except('page'));

        $tiposArticulos = config('tipos.articulos');
        // Generar los últimos tres años
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 2);

        return view('admin.admin_articulos', compact('articulos', 'tiposArticulos', 'years'));
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
        $tiposArticulos = config('tipos.articulos');
        $autores = Autor::all();
        return view('admin.modals.modal_articulo', compact('autores', 'tiposArticulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'issn_articulo' => 'required|string|max:9|unique:tb_articulo,ISSN_ARTICULO',
            'titulo_articulo' => 'required|string|max:255',
            'resumen_articulo' => 'required|string',
            'fecha_articulo' => 'required|date',
            'revista_articulo' => 'required|string|max:100',
            'tipo_articulo' => 'required|string|max:100',
            'url_revista_articulo' => 'required|url',
            'url_articulo' => 'nullable|file|mimes:pdf',
            'url_imagen_articulo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $articulo = new Articulo();
        $articulo->ISSN_ARTICULO = $request->issn_articulo;
        $articulo->TITULO_ARTICULO = $request->titulo_articulo;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulo;
        $articulo->FECHA_ARTICULO = $request->fecha_articulo;
        $articulo->REVISTA_ARTICULO = $request->revista_articulo;
        $articulo->TIPO_ARTICULO = $request->tipo_articulo;
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulo;
        $articulo->ELIMINADO_ARTICULO = false;

        if ($request->hasFile('url_articulo')) {
            $articulo->URL_ARTICULO = $request->file('url_articulo')->store('articulos');
        }

        if ($request->hasFile('url_imagen_articulo')) {
            $articulo->URL_IMAGEN_ARTICULO = $request->file('url_imagen_articulo')->store('imagenes');
        }

        $articulo->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $articulo->autores()->sync($autores);

        return redirect()->route('admin.dashboard')->with('success', 'Artículo creado exitosamente.');
    }

    public function edit($id)
    {
        $tiposArticulos = config('tipos.articulos');
        $articulo = Articulo::findOrFail($id);
        $autores = Autor::all();
        return view('admin.modals.modal_articulo', compact('articulo', 'autores', 'tiposArticulos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'issn_articulo' => 'required|string|max:9|unique:tb_articulo,ISSN_ARTICULO,' . $id . ',ID_ARTICULO',
            'titulo_articulo' => 'required|string|max:255',
            'resumen_articulo' => 'required|string',
            'fecha_articulo' => 'required|date',
            'revista_articulo' => 'required|string|max:100',
            'tipo_articulo' => 'required|string|max:100',
            'url_revista_articulo' => 'nullable|url',
            'url_articulo' => 'nullable|file|mimes:pdf',
            'url_imagen_articulo' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $articulo = Articulo::findOrFail($id);
        $articulo->ISSN_ARTICULO = $request->issn_articulo;
        $articulo->TITULO_ARTICULO = $request->titulo_articulo;
        $articulo->RESUMEN_ARTICULO = $request->resumen_articulo;
        $articulo->FECHA_ARTICULO = $request->fecha_articulo;
        $articulo->REVISTA_ARTICULO = $request->revista_articulo;
        $articulo->TIPO_ARTICULO = $request->tipo_articulo;
        $articulo->URL_REVISTA_ARTICULO = $request->url_revista_articulo;

        if ($request->hasFile('url_articulo')) {
            $articulo->URL_ARTICULO = $request->file('url_articulo')->store('articulos');
        }

        if ($request->hasFile('url_imagen_articulo')) {
            $articulo->URL_IMAGEN_ARTICULO = $request->file('url_imagen_articulo')->store('imagenes');
        }

        $articulo->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $articulo->autores()->sync($autores);

        return redirect()->route('admin.articulos.index')->with('success', 'Artículo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);

        // Realizar eliminación lógica
        $articulo->ELIMINADO_ARTICULO = true;
        $articulo->save();

        return redirect()->route('admin.articulos.index')->with('success', 'Artículo eliminado exitosamente.');
    }

    public function restore($id)
    {
        $articulo = Articulo::findOrFail($id);

        // Restaurar el artículo si fue eliminado hace menos de una semana
        if ($articulo->ELIMINADO_ARTICULO && $articulo->updated_at->gt(now()->subWeek())) {
            $articulo->ELIMINADO_ARTICULO = false;
            $articulo->save();
            return redirect()->route('admin.articulos.index')->with('success', 'Artículo restaurado exitosamente.');
        }

        return redirect()->route('admin.articulos.index')->with('error', 'No se puede restaurar el artículo.');
    }
}
