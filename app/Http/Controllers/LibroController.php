<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Core\Libro;
use App\Traits\FilesTrait;
use App\Traits\AuthorTrait;
use App\Traits\YearTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LibroController extends Controller
{
    use FilesTrait, AuthorTrait, YearTrait;

    public function __construct()
    {
        $this->middleware('auth')->only([
            'adminIndex',
            'store',
            'update',
            'destroy'
        ]);
    }

    /**
     * Muestra los libros.
     */
    public function index(Request $request)
    {
        $query = Libro::with('autores')
            ->select('ID_LIBRO', 'ISBN_LIBRO', 'TITULO_LIBRO', 'CAPITULO_LIBRO', 'FECHA_LIBRO', 'EDITORIAL_LIBRO', 'URL_IMAGEN_LIBRO');

        $this->applyFilters($query, $request);

        $libros = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($libros as $libro) {
                foreach ($libro->autores as $autor) {
                    $this->splitAuthorName($autor);
                }

                // ✅ Procesar URLs de imagen
                if ($libro->URL_IMAGEN_LIBRO) {
                    if (!str_starts_with($libro->URL_IMAGEN_LIBRO, 'storage/')) {
                        $libro->URL_IMAGEN_LIBRO = 'storage/' . $libro->URL_IMAGEN_LIBRO;
                    }
                    if (!str_starts_with($libro->URL_IMAGEN_LIBRO, '/')) {
                        $libro->URL_IMAGEN_LIBRO = '/' . $libro->URL_IMAGEN_LIBRO;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'libros' => $libros,
            ]);
        }

        $years = $this->applyYears(2);

        return view('entities.libros.index', compact('years'));
    }

    /**
     * Muestra los libros en el panel de administración.
     */
    public function adminIndex(Request $request)
    {
        $query = Libro::select('tb_libro.*')
            ->distinct()
            ->with(['autores']);

        $this->applyFilters($query, $request);

        $libros = $query->paginate(30)->appends($request->except('page'));

        if ($request->ajax()) {
            foreach ($libros as $libro) {
                if ($libro->URL_IMAGEN_LIBRO && !str_starts_with($libro->URL_IMAGEN_LIBRO, 'storage/')) {
                    $libro->URL_IMAGEN_LIBRO = 'storage/' . $libro->URL_IMAGEN_LIBRO;
                }
            }

            return response()->json([
                'libros' => $libros,
            ]);
        }

        $years = $this->applyYears(2);

        return view('entities.libros.edit', compact('years'));
    }

    /**
     * Muestra el libro.
     */
    public function show($id)
    {
        $libro = Libro::with('autores')->findOrFail($id);

        $libro->increment('VISTA_LIBRO');

        // ✅ Procesar URLs con nueva estructura
        if ($libro->URL_LIBRO) {
            if (!str_starts_with($libro->URL_LIBRO, '/')) {
                $libro->URL_LIBRO = '/' . $libro->URL_LIBRO;
            }
        }

        if ($libro->URL_IMAGEN_LIBRO) {
            if (!str_starts_with($libro->URL_IMAGEN_LIBRO, '/')) {
                $libro->URL_IMAGEN_LIBRO = '/' . $libro->URL_IMAGEN_LIBRO;
            }
        }

        return view('entities.libros.show', compact('libro'));
    }

    /**
     * Descarga el libro.
     */
    public function download($id)
    {
        $libro = Libro::findOrFail($id);

        // 🎯 Incrementar descarga
        $libro->increment('DESCARGA_LIBRO');

        if (empty($libro->URL_LIBRO)) {
            abort(404, 'Archivo no disponible');
        }

        $filePath = public_path(ltrim($libro->URL_LIBRO, '/'));

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        $fileName = Str::slug($libro->TITULO_LIBRO) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    /**
     * Almacena el libro en la base de datos.
     */
    public function store(Request $request)
    {
        // Verificar si el ISBN ya existe
        if (Libro::where('ISBN_LIBRO', $request->isbn_libro)->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ISBN ya existe.'
                ], 422);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'El ISBN ya existe.');
        }

        try {
            $this->validateData($request);

            $libro = new Libro();
            $this->assignData($libro, $request);
            $libro->save();

            $this->assignFiles($libro, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $libro->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Libro creado correctamente.',
                    'libro' => $libro->load(['autores'])
                ]);
            }

            return redirect()->route('entities.dashboard.dashboard')->with('success', 'Libro registrado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el libro: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('entities.dashboard.dashboard')->with('error', 'Error al crear el libro.');
        }
    }

    /**
     * Actualiza el libro en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Verificar que el ISBN no sea igual, siempre y cuando el ID no sea el mismo
        if (Libro::where('ISBN_LIBRO', $request->isbn_libro)->where('ID_LIBRO', '!=', $id)->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El ISBN ya existe.'
                ], 422);
            }
            return redirect()->route('admin.libros.index')->with('error', 'El ISBN ya existe.');
        }

        try {
            $this->validateData($request, $id);

            $libro = Libro::findOrFail($id);
            $this->assignData($libro, $request);
            $libro->save();

            $this->assignFiles($libro, $request);

            // Guardar autores
            $autores = $this->handleAutores($request);
            $libro->autores()->sync($autores);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Libro actualizado.',
                    'libro' => $libro->load(['autores'])
                ]);
            }

            return redirect()->route('admin.libros.index')->with('success', 'Libro actualizado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el libro: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.libros.index')->with('error', 'Error al actualizar el libro.');
        }
    }

    /**
     * Elimina el libro de la base de datos.
     */
    public function destroy($id, Request $request)
    {
        try {
            $libro = Libro::findOrFail($id);
            $tituloLibro = $libro->TITULO_LIBRO;

            // 🎯 Eliminar archivos asociados
            if ($libro->URL_LIBRO) {
                $this->deleteFile($libro->URL_LIBRO);
            }

            if ($libro->URL_IMAGEN_LIBRO) {
                $this->deleteFile($libro->URL_IMAGEN_LIBRO);
            }

            $libro->autores()->detach();
            $libro->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Libro '{$tituloLibro}' eliminado correctamente."
                ]);
            }

            return redirect()->route('admin.libros.index')
                ->with('success', "Libro '{$tituloLibro}' eliminado correctamente.");
        } catch (\Exception $e) {
            Log::error('Error al eliminar libro:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error inesperado al eliminar el libro.'
                ], 500);
            }

            return redirect()->route('admin.libros.index')
                ->with('error', 'Error inesperado al eliminar el libro.');
        }
    }

    /**
     * Aplica los filtros a la consulta de libros.
     */
    private function applyFilters($query, Request $request)
    {
        Log::info('=== DEBUG FILTROS LIBROS ===');
        Log::info('Request completo:', $request->all());

        if ($request->has('search') && !is_null($request->input('search'))) {
            $search = $request->input('search');
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('TITULO_LIBRO', 'like', "%$term%")
                        ->orWhere('ISBN_LIBRO', 'like', "%$term%")
                        ->orWhere('CAPITULO_LIBRO', 'like', "%$term%")
                        ->orWhere('EDITORIAL_LIBRO', 'like', "%$term%")
                        ->orWhere('DOI_LIBRO', 'like', "%$term%")
                        ->orWhereHas('autores', function ($q) use ($term) {
                            $q->where('NOMBRE_AUTOR', 'like', "%$term%")
                                ->orWhere('APELLIDO_AUTOR', 'like', "%$term%");
                        });
                }
            });
        }

        $query = $this->applyYearFilters($query, $request, 'FECHA_LIBRO', true);

        $ordenar = $request->input('ordenar', 'fecha_desc');
        switch ($ordenar) {
            case 'titulo_asc':
                $query->orderBy('TITULO_LIBRO', 'asc');
                break;
            case 'titulo_desc':
                $query->orderBy('TITULO_LIBRO', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('FECHA_LIBRO', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('FECHA_LIBRO', 'desc');
                break;
            case 'editorial_asc':
                $query->orderBy('EDITORIAL_LIBRO', 'asc');
                break;
            case 'editorial_desc':
                $query->orderBy('EDITORIAL_LIBRO', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Valida que los datos del libro sean los correctos.
     */
    private function validateData(Request $request, $id = null)
    {
        $uniqueRule = $id ? 'unique:tb_libro,ISBN_LIBRO,' . $id . ',ID_LIBRO' : 'unique:tb_libro,ISBN_LIBRO';

        $request->validate([
            'isbn_libro' => 'required|string|max:20|' . $uniqueRule,
            'titulo_libro' => 'required|string|max:255',
            'capitulo_libro' => 'nullable|string|max:255',
            'fecha_libro' => 'required|date',
            'editorial_libro' => 'required|string|max:100',
            'doi_libro' => 'nullable|string|max:255',
            'url_libro' => 'nullable|file|mimes:pdf',
            'url_imagen_libro' => 'nullable|file|mimes:png,jpg,jpeg,webp',
            'nombre_autores' => 'required|array',
            'apellido_autores' => 'required|array',
        ]);
    }

    /**
     * Asigna los datos del libro a la instancia del modelo.
     */
    private function assignData(Libro $libro, Request $request)
    {
        $libro->ISBN_LIBRO = $request->isbn_libro;
        $libro->TITULO_LIBRO = $request->titulo_libro;
        $libro->CAPITULO_LIBRO = $request->capitulo_libro;
        $libro->FECHA_LIBRO = $request->fecha_libro;
        $libro->EDITORIAL_LIBRO = $request->editorial_libro;
        $libro->DOI_LIBRO = $request->doi_libro;

        if (!$libro->exists) {
            $libro->VISTA_LIBRO = 0;
            $libro->DESCARGA_LIBRO = 0;
        }

        $libro->ID_USUARIO = Auth::id();
    }

    /**
     * Asigna los archivos del libro.
     */
    private function assignFiles(Libro $libro, Request $request)
    {
        $uploadedFiles = $this->handleMultipleFileUploadsSimple($request, 'libros', $libro->ID_LIBRO, [
            'url_libro',
            'url_imagen_libro'
        ]);

        if (isset($uploadedFiles['url_libro'])) {
            // Eliminar archivo anterior
            if ($libro->URL_LIBRO) {
                $this->deleteFile($libro->URL_LIBRO);
            }
            $libro->URL_LIBRO = $uploadedFiles['url_libro'];
        }

        if (isset($uploadedFiles['url_imagen_libro'])) {
            // Eliminar imagen anterior
            if ($libro->URL_IMAGEN_LIBRO) {
                $this->deleteFile($libro->URL_IMAGEN_LIBRO);
            }
            $libro->URL_IMAGEN_LIBRO = $uploadedFiles['url_imagen_libro'];
        }

        // Guardar cambios de archivos
        if (!empty($uploadedFiles)) {
            $libro->save();
        }
    }
}