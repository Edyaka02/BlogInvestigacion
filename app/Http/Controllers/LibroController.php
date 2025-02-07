<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LibroController extends Controller
{

    private function applyFilters($query, Request $request, $column = 'YEAR_LIBRO')
    {
        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search); // Divide el término de búsqueda en palabras

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('TITULO_LIBRO', 'like', "%$term%")
                        ->orWhere('CAPITULO_LIBRO', 'like', "%$term%")
                        ->orWhere('ISBN_LIBRO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        });
                }
            });
        }

        if ($request->input('anio') === 'intervalo' && $request->has('anio_inicio') && $request->has('anio_fin') && !is_null($request->input('anio_inicio')) && !is_null($request->input('anio_fin'))) {
            $anio_inicio = $request->input('anio_inicio');
            $anio_fin = $request->input('anio_fin');

            // Validar que el año final no sea menor al año de inicio
            if ($anio_fin < $anio_inicio) {
                return redirect()->back()->with('error', 'El año final no puede ser menor que el año de inicio.');
            }

            $query->whereBetween($column, [$anio_inicio, $anio_fin]);
        } elseif ($request->has('anio') && !empty($request->input('anio')) && $request->input('anio') !== 'todos') {
            $anios = $request->input('anio');
            if (!is_array($anios)) {
                $anios = [$anios];
            }
            $query->whereIn($column, $anios);
        }

        $ordenar = $request->input('ordenar', 'fecha_desc'); 

        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_LIBRO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_LIBRO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('YEAR_LIBRO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('YEAR_LIBRO', 'desc');
                break;
        }

        // Generar los últimos tres años
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 2);

        return $years;
    }

    public function index()
    {
        $libros = Libro::with('autores')
            ->select('ID_LIBRO', 'TITULO_LIBRO', 'YEAR_LIBRO', 'URL_IMAGEN_LIBRO')
            ->get();
        return view('libros.libro', compact('libros'));
    }

    public function adminIndex(Request $request)
    {
        $query = Libro::with('autores')->where('ELIMINADO_LIBRO', false);
        $years = $this->applyFilters($query, $request, 'YEAR_LIBRO');

        $libros = $query->paginate(20)->appends($request->except('page'));

        $route = route('admin.libros.index');

        return view('admin.admin_libros', compact('libros', 'years', 'route'));
    }

    public function show($id)
    {
        $libro = Libro::with('autores')->findOrFail($id);

        // Generar URLs públicas
        $libro->URL_LIBRO = Storage::url($libro->URL_LIBRO);
        $libro->URL_IMAGEN_LIBRO = Storage::url($libro->URL_IMAGEN_LIBRO);

        return view('libros.libro_detalle', compact('libro'));
    }

    public function create()
    {
        $autores = Autor::all();
        return view('admin.modals.modal_libro', compact('autores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_libro' => 'required|string|max:150',
            'capitulo_libro' => 'required|string|max:150',
            'isbn_libro' => 'nullable|string|max:20|unique:tb_libro,ISBN_LIBRO',
            'year_libro' => 'required|integer',
            'editorial_libro' => 'required|string|max:100',
            'doi_libro' => 'nullable|string',
            'url_libro' => 'nullable|file|mimes:pdf',
            'url_imagen_libro' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $libro = new Libro();
        $libro->TITULO_LIBRO = $request->titulo_libro;
        $libro->CAPITULO_LIBRO = $request->capitulo_libro;
        $libro->ISBN_LIBRO = $request->isbn_libro;
        $libro->YEAR_LIBRO = $request->year_libro;
        $libro->EDITORIAL_LIBRO = $request->editorial_libro;
        $libro->DOI_LIBRO = $request->doi_libro;
        $libro->ELIMINADO_LIBRO = false;

        if ($request->hasFile('url_libro')) {
            $libro->URL_LIBRO = $request->file('url_libro')->store('libros');
        }

        if ($request->hasFile('url_imagen_libro')) {
            $libro->URL_IMAGEN_LIBRO = $request->file('url_imagen_libro')->store('imagenes');
        }

        $libro->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $libro->autores()->sync($autores);

        return redirect()->route('admin.dashboard')->with('success', 'Libro creado exitosamente.');
    }

    public function edit($id)
    {
        $libro = Libro::findOrFail($id);
        $autores = Autor::all();
        return view('admin.modals.modal_libro', compact('libro', 'autores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo_libro' => 'required|string|max:150',
            'capitulo_libro' => 'required|string|max:150',
            'isbn_libro' => 'nullable|string|max:20|unique:tb_libro,ISBN_LIBRO,' . $id . ',ID_LIBRO',
            'year_libro' => 'required|integer',
            'editorial_libro' => 'required|string|max:100',
            'doi_libro' => 'nullable|string',
            'url_libro' => 'nullable|file|mimes:pdf',
            'url_imagen_libro' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);

        $libro = Libro::findOrFail($id);
        $libro->TITULO_LIBRO = $request->titulo_libro;
        $libro->CAPITULO_LIBRO = $request->capitulo_libro;
        $libro->ISBN_LIBRO = $request->isbn_libro;
        $libro->YEAR_LIBRO = $request->year_libro;
        $libro->EDITORIAL_LIBRO = $request->editorial_libro;
        $libro->DOI_LIBRO = $request->doi_libro;

        if ($request->hasFile('url_libro')) {
            $libro->URL_LIBRO = $request->file('url_libro')->store('libros');
        }

        if ($request->hasFile('url_imagen_libro')) {
            $libro->URL_IMAGEN_LIBRO = $request->file('url_imagen_libro')->store('imagenes');
        }

        $libro->save();

        // Guardar autores
        $autores = [];
        foreach ($request->nombre_autores as $index => $nombre) {
            $autor = Autor::firstOrCreate([
                'NOMBRE_AUTOR' => $nombre,
                'APELLIDO_AUTOR' => $request->apellido_autores[$index]
            ]);
            $autores[$autor->ID_AUTOR] = ['ORDEN_AUTOR' => $index + 1];
        }
        $libro->autores()->sync($autores);

        return redirect()->route('admin.libros.index')->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);

        // Realizar eliminación lógica
        $libro->ELIMINADO_LIBRO = true;
        $libro->save();

        return redirect()->route('admin.libros.index')->with('success', 'Libro eliminado exitosamente.');
    }

    public function restore($id)
    {
        $libro = Libro::findOrFail($id);

        // Restaurar el libro si fue eliminado hace menos de una semana
        if ($libro->ELIMINADO_LIBRO && $libro->updated_at->gt(now()->subWeek())) {
            $libro->ELIMINADO_LIBRO = false;
            $libro->save();

            return redirect()->route('admin.libros.index')->with('success', 'Libro restaurado exitosamente.');
        }

        return redirect()->route('admin.libros.index')->with('error', 'No se puede restaurar el libro.');
    }
}
