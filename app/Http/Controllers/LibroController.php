<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Autor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\Archivos;
use App\Traits\AutorTrait;
use App\Traits\YearTrait;

class LibroController extends Controller
{
    use Archivos, AutorTrait, YearTrait;

    public function index(Request $request)
    {
        $query = Libro::with('autores')
            ->where('ELIMINADO_LIBRO', false)
            ->select('ID_LIBRO', 'TITULO_LIBRO', 'YEAR_LIBRO', 'ISBN_LIBRO', 'URL_IMAGEN_LIBRO');

        $hasResults = $this->applyFilters($query, $request);
        $years = $this->applyYears(2);

        $libros = $query->paginate(20)->appends($request->except('page'));

        $route = route('libros.libro');


        foreach ($libros as $libro) {
            foreach ($libro->autores as $autor) {
                $this->splitAuthorName($autor);
            }
            $libro->URL_IMAGEN_LIBRO = Storage::url($libro->URL_IMAGEN_LIBRO);
        }

        return view('libros.libro', compact('libros', 'years', 'route', 'hasResults'));
    }

    public function adminIndex(Request $request)
    {
        $query = Libro::with('autores')->where('ELIMINADO_LIBRO', false);
        $hasResults = $this->applyFilters($query, $request);
        $years = $this->applyYears(2);

        $libros = $query->paginate(20)->appends($request->except('page'));

        $route = route('admin.libros.index');

        return view('admin.admin_libros', compact('libros', 'years', 'route', 'hasResults'));
    }

    public function show($id)
    {
        $libro = Libro::with('autores')->findOrFail($id);

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
        // Verificar si el ISSN ya existe
        if (Libro::where('ISBN_LIBRO', $request->isbn_libro)->exists()) {
            return redirect()->route('admin.dashboard')->with('error', 'El ISBN ya existe.');
        }

        $this->validateLibro($request);

        $libro = new Libro();
        $this->assignLibroData($libro, $request);
        $libro->ELIMINADO_LIBRO = false;

        $libro->save();

        $autores = $this->handleAutores($request);
        $libro->autores()->sync($autores);

        return redirect()->route('admin.dashboard')->with('success', 'Libro registrado.');
    }

    public function update(Request $request, $id)
    {
        $this->validateLibro($request, $id);

        $libro = Libro::findOrFail($id);
        $this->assignLibroData($libro, $request);

        $libro->save();

        $autores = $this->handleAutores($request);
        $libro->autores()->sync($autores);

        return redirect()->route('admin.libros.index')->with('success', 'Libro actualizado.');
    }

    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->ELIMINADO_LIBRO = true;
        $libro->save();

        return redirect()->route('admin.libros.index')->with('success', 'Libro eliminado.');
    }

    public function restore($id)
    {
        $libro = Libro::findOrFail($id);

        if ($libro->ELIMINADO_LIBRO && $libro->updated_at->gt(now()->subWeek())) {
            $libro->ELIMINADO_LIBRO = false;
            $libro->save();

            return redirect()->route('admin.libros.index')->with('success', 'Libro restaurado exitosamente.');
        }

        return redirect()->route('admin.libros.index')->with('error', 'No se puede restaurar el libro.');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

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

        $query = $this->applyYearFilters($query, $request, 'YEAR_LIBRO', false);

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

        return $query->exists();
    }

    private function validateLibro(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:tb_libro,ISBN_LIBRO,' . $id . ',ID_LIBRO' : 'unique:tb_libro,ISBN_LIBRO';

        $request->validate([
            'titulo_libro' => 'required|string|max:150',
            'capitulo_libro' => 'required|string|max:150',
            'isbn_libro' => 'nullable|string|max:20|' . $uniqueRule,
            'year_libro' => 'required|integer',
            'editorial_libro' => 'required|string|max:100',
            'doi_libro' => 'nullable|string',
            'url_libro' => 'nullable|file|mimes:pdf',
            'url_imagen_libro' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    private function assignLibroData(Libro $libro, Request $request)
    {
        $libro->TITULO_LIBRO = $request->titulo_libro;
        $libro->CAPITULO_LIBRO = $request->capitulo_libro;
        $libro->ISBN_LIBRO = $request->isbn_libro;
        $libro->YEAR_LIBRO = $request->year_libro;
        $libro->EDITORIAL_LIBRO = $request->editorial_libro;
        $libro->DOI_LIBRO = $request->doi_libro;

        if ($request->hasFile('url_libro')) {
            $libro->URL_LIBRO = $this->handleFileUpload($request, 'url_libro', 'libros');
        }

        if ($request->hasFile('url_imagen_libro')) {
            $libro->URL_IMAGEN_LIBRO = $this->handleFileUpload($request, 'url_imagen_libro', 'imagenes');
        }
    }
}
